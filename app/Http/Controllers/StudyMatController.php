<?php

namespace App\Http\Controllers;
use App\Subject;
use App\Area;
use App\Section;
use Validator;
use App\StudyMat;
use App\StudyMatSampleQues;
use App\SubjectExam;
use App\Exam;

use Illuminate\Http\Request;

class StudyMatController extends Controller
{
    public function index() {
        $fetch_all_study_mat = StudyMat::get()->toArray();
        foreach ($fetch_all_study_mat as $key => $value) {
            $fetch_subject = Subject::where('id', $value['subject_id'])->get()->toArray();
            $fetch_area = Area::where('id', $value['area_id'])->get()->toArray();
            $fetch_section = Section::where('id', $value['section_id'])->get()->toArray();
            $fetch_all_study_mat[$key]['subject'] = $fetch_subject[0]['sub_full_name'];
            $fetch_all_study_mat[$key]['area'] = $fetch_area[0]['name'];
            $fetch_all_study_mat[$key]['section'] = $fetch_section[0]['name'];
            $exam_name = '';
            $exam_ids = explode(',', $value['exam_id']);
            foreach ($exam_ids as $e_id) {
                $fetch_exam_details = Exam::find($e_id)->toArray();
                $exam_name .= $fetch_exam_details['name'] . ', ';
            }
            $exam_name = rtrim($exam_name, ', ');
            $fetch_all_study_mat[$key]['exam'] = $exam_name;
        }
    	return view('frontend.studymat.listings')->with('fetch_all_study_mat', $fetch_all_study_mat);
    }

    public function add_study_mat_view() {
    	$fetch_all_subject = Subject::where('status', '1')->pluck('sub_full_name', 'id')->toArray();
    	return view('frontend.studymat.add')->with('fetch_all_subject', $fetch_all_subject);
    }

    public function fetch_subject_wise_exam(Request $request) {
        $tempArray = array();
        $subject_id = $request->subject_id;
        $fetch_exam_id = SubjectExam::where('subject_id', $subject_id)->get()->toArray();
        foreach ($fetch_exam_id as $key => $value) {
            $exam_id = $value['exam_id'];
            $fetch_exam_details = Exam::find($exam_id)->toArray();
            $exam_details_array['exam_id'] = $fetch_exam_details['id'];
            $exam_details_array['exam_name'] = $fetch_exam_details['name'];
            $tempArray[] = $exam_details_array;
        }
        return response()->json(['tempArray' => $tempArray]);
    }

    public function fetch_subject_wise_area(Request $request) {
    	$tempArray = array();
    	$subject_id = $request->subject_id;
    	$fetch_area_details = Area::where([['status', '=', '1'],['subject_id', '=', $subject_id]])->get()->toArray();
    	foreach ($fetch_area_details as $key => $value) {
    		$area_details_array['area_id'] = $value['id'];
    		$area_details_array['area_name'] = $value['name'];
    		$tempArray[] = $area_details_array;
    	}
    	return response()->json(['tempArray' => $tempArray]);
    }

    public function fetch_area_wise_section(Request $request) {
    	$tempArray = array();
    	$area_id = $request->area_id;
    	$fetch_section_details = Section::where([['area_id', $area_id]])->get()->toArray();
    	foreach ($fetch_section_details as $key => $value) {
    		$section_details_array['id'] = $value['id'];
    		$section_details_array['name'] = $value['name'];
    		$tempArray[] = $section_details_array;
    	}
    	return response()->json(['tempArray' => $tempArray]);
    }

    public function study_mat_submit(Request $request) {
    	$validator = Validator::make($request->all(),[
    		'subject' => 'required',
            'exam' => 'required',
    		'area' => 'required',
    		'section' => 'required|unique:study_mats,section_id,'.$request->section
    	],[
    		'subject.required' => 'Please select subject',
            'exam.required' => 'Please select exam',
    		'area.required' => 'Please select area',
    		'section.required' => 'Please select section',
            'section.unique' => 'Study material already exists. Please choose another section'
    	]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => $validator->messages()->first(),
                'code' => 500
            ]);
        }

    	$video_arr = array();
    	$pdf_arr = array();
    	$doc_arr = array();

    	if ($request->hasFile('video_files')) {
    		foreach ($request->file('video_files') as $file) {
    			$videoName = time().'_'.$file->getClientOriginalName();
				//original destination path
				$destinationPath = public_path().'/upload/study_video/';
				$file->move($destinationPath, $videoName);
                $key = array_search($file->getClientOriginalName(), $request->video_order);
				$video_arr[] = array(
					'video' => $videoName,
					'video_order' => $key
		     	);
    		}
		} else {
			$video_arr = array();
		}

		if ($request->hasFile('pdf_files')) {
    		foreach ($request->file('pdf_files') as $file) {
    			$pdfName = time().'_'.$file->getClientOriginalName();
				//original destination path
				$destinationPath = public_path().'/upload/study_pdf/';
				$file->move($destinationPath, $pdfName);
                $key = array_search($file->getClientOriginalName(), $request->pdf_order);
				$pdf_arr[] = array(
					'pdf' => $pdfName,
					'pdf_order' => $key
		     	);
    		}
		} else {
			$pdf_arr = array();
		}

		if ($request->hasFile('doc_files')) {
    		foreach ($request->file('doc_files') as $file) {
    			$docName = time().'_'.$file->getClientOriginalName();
				//original destination path
				$destinationPath = public_path().'/upload/study_doc/';
				$file->move($destinationPath, $docName);
                $key = array_search($file->getClientOriginalName(), $request->doc_order);
				$doc_arr[] = array(
					'doc' => $docName,
					'doc_order' => $key
		     	);
    		}
		} else {
			$doc_arr = array();
		}

        $exam_ids = $request->exam;
        $exam_ids = implode(",", $exam_ids);

        $sample = array_combine($request->sample_questions, $request->sample_answers);

    	$add = new StudyMat();
    	$add->subject_id = $request->subject;
        $add->exam_id = $exam_ids;
    	$add->area_id = $request->area;
    	$add->section_id = $request->section;
		$add->video = serialize($video_arr);
		$add->pdf = serialize($pdf_arr);
		$add->document = serialize($doc_arr);
        $add->description = $request->description;
		$add->duration = $request->duration;

		if ($add->save()) {
            $studymat_id = $add->id;
            foreach ($sample as $question => $answer) {
                $add_sample_ques = new StudyMatSampleQues();
                $add_sample_ques->study_mat_id = $studymat_id;
                $add_sample_ques->questions = $question;
                $add_sample_ques->answers = $answer;
                $add_sample_ques->save();
            }
            return 1;
        } else {
            return 0;
        }
    }

    public function edit_study_mat_view($id) {
        $fetch_study_mat = StudyMat::find($id)->toArray();
        $fetch_study_videos = unserialize($fetch_study_mat['video']);
        usort($fetch_study_videos, function($a, $b) {
            $t1 = $a['video_order'];
            $t2 = $b['video_order'];
            return $t1 - $t2;
        });
        $fetch_study_pdfs = unserialize($fetch_study_mat['pdf']);
        usort($fetch_study_pdfs, function($a, $b) {
            $t1 = $a['pdf_order'];
            $t2 = $b['pdf_order'];
            return $t1 - $t2;
        });
        $fetch_study_documents = unserialize($fetch_study_mat['document']);
        usort($fetch_study_documents, function($a, $b) {
            $t1 = $a['doc_order'];
            $t2 = $b['doc_order'];
            return $t1 - $t2;
        });
        $fetch_all_subject = Subject::where('status', '1')->pluck('sub_full_name', 'id')->toArray();
        $fetch_all_exam = Exam::where('status','1')->pluck('name','id')->toArray();
        $fetch_all_area = Area::where('status', '1')->pluck('name', 'id')->toArray();
        $fetch_all_section = Section::pluck('name', 'id')->toArray();
        $exam_ids = explode(",", $fetch_study_mat['exam_id']);

        $fetch_sample_ques = StudyMatSampleQues::where('study_mat_id', $id)->pluck('answers', 'questions')->toArray();

        return view('frontend.studymat.edit')->with(['fetch_study_mat' => $fetch_study_mat, 'fetch_all_exam' => $fetch_all_exam,'fetch_study_videos' => $fetch_study_videos,'fetch_study_pdfs' => $fetch_study_pdfs,'fetch_study_documents' => $fetch_study_documents,'fetch_all_subject' => $fetch_all_subject,'fetch_all_area' => $fetch_all_area,'fetch_all_section' => $fetch_all_section, 'exam_ids' => $exam_ids, 'fetch_sample_ques' => $fetch_sample_ques]);
    }

    public function study_mat_update(Request $request) {
        $id = $request->study_id;
        $validator = Validator::make($request->all(),[
            'subject' => 'required',
            'exam' => 'required',
            'area' => 'required',
            'section' => 'required|unique:study_mats,section_id,'.$request->section
        ],[
            'subject.required' => 'Please select subject',
            'exam.required' => 'Please select exam',
            'area.required' => 'Please select area',
            'section.required' => 'Please select section',
            'section.unique' => 'Study material already exists. Please choose another section'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => $validator->messages()->first(),
                'code' => 500
            ]);
        }

        $studymat = StudyMat::find($id);

        $video_arr = array();
        $pdf_arr = array();
        $doc_arr = array();

        $video_arr = unserialize($studymat['video']);
        $pdf_arr = unserialize($studymat['pdf']);
        $doc_arr = unserialize($studymat['document']);

        if ($request->hasFile('video_files')) {
            foreach ($request->file('video_files') as $file) {
                $videoName = time().'_'.$file->getClientOriginalName();
                //original destination path
                $destinationPath = public_path().'/upload/study_video/';
                $file->move($destinationPath, $videoName);
                $key = array_search($file->getClientOriginalName(), $request->video_order);
                $video_arr[] = array(
                    'video' => $videoName,
                    'video_order' => $key
                );
            }
        } else {
            $temp_video_arr = array();
            foreach ($video_arr as $file) {
                $key = array_search($file['video'], $request->video_order);
                $temp_video_arr[] = array(
                    'video' => $file['video'],
                    'video_order' => $key
                );
            }
            $video_arr = $temp_video_arr;
        }

        if ($request->hasFile('pdf_files')) {
            foreach ($request->file('pdf_files') as $file) {
                $pdfName = time().'_'.$file->getClientOriginalName();
                //original destination path
                $destinationPath = public_path().'/upload/study_pdf/';
                $file->move($destinationPath, $pdfName);
                $key = array_search($file->getClientOriginalName(), $request->pdf_order);
                $pdf_arr[] = array(
                    'pdf' => $pdfName,
                    'pdf_order' => $key
                );
            }
        } else {
            $temp_pdf_arr = array();
            foreach ($pdf_arr as $file) {
                $key = array_search($file['pdf'], $request->pdf_order);
                $temp_pdf_arr[] = array(
                    'pdf' => $file['pdf'],
                    'pdf_order' => $key
                );
            }
            $pdf_arr = $temp_pdf_arr;
        }

        if ($request->hasFile('doc_files')) {
            foreach ($request->file('doc_files') as $file) {
                $docName = time().'_'.$file->getClientOriginalName();
                //original destination path
                $destinationPath = public_path().'/upload/study_doc/';
                $file->move($destinationPath, $docName);
                $key = array_search($file->getClientOriginalName(), $request->doc_order);
                $doc_arr[] = array(
                    'doc' => $docName,
                    'doc_order' => $key
                );
            }
        } else {
            $temp_doc_arr = array();
            foreach ($doc_arr as $file) {
                $key = array_search($file['doc'], $request->doc_order);
                $temp_doc_arr[] = array(
                    'doc' => $file['doc'],
                    'doc_order' => $key
                );
            }
            $doc_arr = $temp_doc_arr;
        }

        $sample = array_combine($request->sample_questions, $request->sample_answers);

        $studymat->subject_id = $request->subject;
        $studymat->area_id = $request->area;
        $studymat->section_id = $request->section;
        $studymat->video = serialize($video_arr);
        $studymat->pdf = serialize($pdf_arr);
        $studymat->document = serialize($doc_arr);
        $studymat->description = $request->description;
        $studymat->duration = $request->duration;

        if ($studymat->save()) {
            $study_mat_sample_ques = StudyMatSampleQues::where('study_mat_id', $id);
            $study_mat_sample_ques->delete();
            foreach ($sample as $question => $answer) {
                $add_sample_ques = new StudyMatSampleQues();
                $add_sample_ques->study_mat_id = $id;
                $add_sample_ques->questions = $question;
                $add_sample_ques->answers = $answer;
                $add_sample_ques->save();
            }
            return 1;
        } else {
            return 0;
        }
    }

    public function study_mat_delete(Request $request, $study_id) {
        $study_mat = StudyMat::find($study_id);
        if ($study_mat->delete()) {
            $request->session()->flash("submit-status",'Study Material deleted successfully.');
            return redirect('/study_mat/');
        } else {
            $request->session()->flash("error-status",'Study Material deletion failed!');
            return redirect('/study_mat/');
        }
    }

    public function video_delete($study_id, $video_id) {
        $studymat = StudyMat::find($study_id);
        $video_arr = unserialize($studymat['video']);
        unset($video_arr[$video_id]);
        $studymat->video = serialize($video_arr);
        if ($studymat->save()) {
            return 1;
        } else {
            return 0;
        }
    }

    public function pdf_delete($study_id, $pdf_id) {
        $studymat = StudyMat::find($study_id);
        $pdf_arr = unserialize($studymat['pdf']);
        unset($pdf_arr[$pdf_id]);
        $studymat->pdf = serialize($pdf_arr);
        if ($studymat->save()) {
            return 1;
        } else {
            return 0;
        }
    }

    public function doc_delete($study_id, $doc_id) {
        $studymat = StudyMat::find($study_id);
        $doc_arr = unserialize($studymat['document']);
        unset($doc_arr[$doc_id]);
        $studymat->document = serialize($doc_arr);
        if ($studymat->save()) {
            return 1;
        } else {
            return 0;
        }
    }
}

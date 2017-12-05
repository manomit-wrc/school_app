<?php

namespace App\Http\Controllers;
use App\Subject;
use App\Area;
use App\Section;
use Validator;
use App\StudyMat;

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
        }
    	return view('frontend.studymat.listings')->with('fetch_all_study_mat', $fetch_all_study_mat);
    }

    public function add_study_mat_view() {
    	$fetch_all_subject = Subject::where('status', '1')->pluck('sub_full_name', 'id')->toArray();
    	return view('frontend.studymat.add')->with('fetch_all_subject', $fetch_all_subject);
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

    public function study_mat_submit (Request $request) {
    	Validator::make($request->all(),[
    		'subject' => 'required',
    		'area' => 'required',
    		'section' => 'required'
    	],[
    		'subject.required' => 'Please select subject',
    		'area.required' => 'Please select area',
    		'section.required' => 'Please select section'
    	])->validate();

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

    	$add = new StudyMat();
    	$add->subject_id = $request->subject;
    	$add->area_id = $request->area;
    	$add->section_id = $request->section;
		$add->video = serialize($video_arr);
		$add->pdf = serialize($pdf_arr);
		$add->document = serialize($doc_arr);
		$add->description = $request->description;

		if ($add->save()) {
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
        $fetch_all_area = Area::where('status', '1')->pluck('name', 'id')->toArray();
        $fetch_all_section = Section::pluck('name', 'id')->toArray();
        return view('frontend.studymat.edit')->with(['fetch_study_mat' => $fetch_study_mat,'fetch_study_videos' => $fetch_study_videos,'fetch_study_pdfs' => $fetch_study_pdfs,'fetch_study_documents' => $fetch_study_documents,'fetch_all_subject' => $fetch_all_subject,'fetch_all_area' => $fetch_all_area,'fetch_all_section' => $fetch_all_section]);
    }
}

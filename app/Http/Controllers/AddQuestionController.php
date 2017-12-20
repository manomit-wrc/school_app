<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Subject;
use App\Exam;
use App\Area;
use App\Section;
use Validator;
use App\QuestionAnswer;
use App\SubjectExam;
use Image;
use DB;

class AddQuestionController extends Controller
{
    public function index() {
    	$fetch_all_question = QuestionAnswer::with('subject', 'area', 'section')->where('status','1')->orderby('id','desc')->get()->toArray();
    	foreach ($fetch_all_question as $key => $value) {
    		$exam_name = '';
    		$exam_ids = explode(',', $value['exam_id']);
    		foreach ($exam_ids as $e_id) {
    			$fetch_exam_details = Exam::find($e_id)->toArray();
	    		$exam_name .= $fetch_exam_details['name'] . ', ';
    		}
        	$exam_name = rtrim($exam_name, ', ');
        	$fetch_all_question[$key]['exam'] = $exam_name;
        }
        $fetch_all_subject = Subject::where('status','1')->pluck('sub_full_name', 'id')->toArray();
        $fetch_all_exam = Exam::where('status','1')->pluck('name', 'id')->toArray();
        $fetch_all_area = Area::where('status','1')->pluck('name', 'id')->toArray();
        $fetch_all_section = Section::pluck('name', 'id')->toArray();
    	return view ('frontend.qustion.listings')->with('fetch_all_question', $fetch_all_question)->with('fetch_all_subject', $fetch_all_subject)->with('fetch_all_exam', $fetch_all_exam)->with('fetch_all_area', $fetch_all_area)->with('fetch_all_section', $fetch_all_section);
    }

    public function add_qustion_view() {
    	$fetch_all_subject = Subject::where('status', '1')->pluck('sub_full_name', 'id')->toArray();
    	$fetch_all_exam = Exam::where('status','1')->pluck('name', 'id')->toArray();
    	return view ('frontend.qustion.add')->with('fetch_all_subject', array_unique($fetch_all_subject))->with('fetch_all_exam', $fetch_all_exam);
    }

    public function fetch_exam_subject_wise(Request $request) {
    	$tempArray = array();
    	$subject_id = $request->subject_id;
    	$fetch_exam_id = SubjectExam::with('exams')->where('subject_id', $subject_id)->get()->toArray();

    	foreach ($fetch_exam_id as $key => $value) {
    		$exam_id = $value['exam_id'];
    		$fetch_exam_details = Exam::where('id', $exam_id)->get()->toArray();
    		$exam_details_array['exam_id'] = $fetch_exam_details[0]['id'];
    		$exam_details_array['exam_name'] = $fetch_exam_details[0]['name'];
    		$tempArray[] = $exam_details_array;
    	}
    	return response()->json(['tempArray'=>$tempArray]);
    }

    public function fetch_area_exam_wise(Request $request) {
    	$tempArray = array();
    	$exam_id = $request->exam_id;
    	$subject_id = $request->subject_id;
    	$fetch_subject_details = Subject::where('id',$subject_id)->get()->toArray();
    	$fetch_area = Area::where([['subject_id',$fetch_subject_details[0]['id']],['status','1']])->get()->toArray();
    	return response()->json(['fetch_area'=>$fetch_area]);
    }

    public function fetch_section_area_wise(Request $request) {
    	$area_id = $request->area_id;
    	$fetch_section_details = Section::where('area_id',$area_id)->get()->toArray();
    	return response()->json(['fetch_section_details'=>$fetch_section_details]);
    }

    public function add_qustion_submit(Request $request) {
    	Validator::make($request->all(),[
    		'subject' => 'required',
    		'exam' => 'required',
    		'area' => 'required',
    		'section' => 'required',
    		'level' => 'required',
    		'question_type' => 'required|in:text,image',
    		'option_type'=> 'required|in:mcq,numeric',
    		'explanation_details' => 'required' ,
    		'explanation_file' => 'mimetypes:image/jpeg,image/png,image/jpg,video/mp4,application/zip,application/pdf|max:6144'
    	],[
    		'subject.required' => 'Please select subject.',
    		'exam.required' => 'Please select exam type.',
    		'area.required' => 'Please select area.',
    		'section.required' => 'Please select section.',
    		'level.required' => 'Please select question level.',
    		'question_type.required' => 'Please select question type.',
    		'option_type.required' => 'Please select option type.',
    		'explanation_details.required' => "Explanation details can't be blank." ,
    		'explanation_file.*.mimetypes' => 'Please upload correct file.',
    		'explanation_file.*.max' => 'Please upload file within 6MB'
    	])->validate();

    	if ($request->hasFile('question_image')) {
            $file = $request->file('question_image');
        	$fileName1 = time().'_'.$file->getClientOriginalName();
        
            //thumb destination path
            $destinationPath_2 = public_path().'/upload/question_file/resize/';
            $img = Image::make($file->getRealPath());
            $img->resize(175, 175, function ($constraint) {
				$constraint->aspectRatio();
            })->save($destinationPath_2.'/'.$fileName1);
            //original destination path
            $destinationPath = public_path().'/upload/question_file/original/';
            $file->move($destinationPath,$fileName1);
        }

    	if (isset($request->option_type_A) && $request->option_type_A == 'text') {
			$optionA_type = $request->option_type_A;
			$optionA = $request->optionA;
		} else {
			$optionA_type = '';
			$optionA = '';
		}

		if (isset($request->option_type_A) && $request->option_type_A == 'image') {
			$optionA_type = $request->option_type_A;
			if ($request->hasFile('optionA_file')) {
	            $file = $request->file('optionA_file');
	        	$fileName = time().'_'.$file->getClientOriginalName();
	        
	            //thumb destination path
	            $destinationPath_2 = public_path().'/upload/answers_file/resize/';
	            $img = Image::make($file->getRealPath());
	            $img->resize(175, 175, function ($constraint) {
	            	$constraint->aspectRatio();
	            })->save($destinationPath_2.'/'.$fileName);
	            //original destination path
	            $destinationPath = public_path().'/upload/answers_file/original/';
	            $file->move($destinationPath,$fileName);
	        }
			$optionA = $fileName;
		}

		if (isset($request->option_type_B) && $request->option_type_B == 'text') {
			$optionB_type = $request->option_type_B;
			$optionB = $request->optionB;
		} else {
			$optionB_type = '';
			$optionB = '';
		}

		if (isset($request->option_type_B) && $request->option_type_B == 'image') {
			$optionB_type = $request->option_type_B;
			if ($request->hasFile('optionB_file')) {
	            $file = $request->file('optionB_file');
	        	$fileName = time().'_'.$file->getClientOriginalName();
	        
	            //thumb destination path
	            $destinationPath_2 = public_path().'/upload/answers_file/resize/';
	            $img = Image::make($file->getRealPath());
	            $img->resize(175, 175, function ($constraint) {
	            	$constraint->aspectRatio();
	            })->save($destinationPath_2.'/'.$fileName);
	            //original destination path
	            $destinationPath = public_path().'/upload/answers_file/original/';
	            $file->move($destinationPath,$fileName);
	        }
			$optionB = $fileName;
		}

		if (isset($request->option_type_C) && $request->option_type_C == 'text') {
			$optionC_type = $request->option_type_C;
			$optionC = $request->optionC;
		} else {
			$optionC_type = '';
			$optionC = '';
		}

		if (isset($request->option_type_C) && $request->option_type_C == 'image') {
			$optionC_type = $request->option_type_C;
			if ($request->hasFile('optionC_file')) {
	            $file = $request->file('optionC_file');
	        	$fileName = time().'_'.$file->getClientOriginalName();
	        
	            //thumb destination path
	            $destinationPath_2 = public_path().'/upload/answers_file/resize/';
	            $img = Image::make($file->getRealPath());
	            $img->resize(175, 175, function ($constraint) {
	            	$constraint->aspectRatio();
	            })->save($destinationPath_2.'/'.$fileName);
	            //original destination path
	            $destinationPath = public_path().'/upload/answers_file/original/';
	            $file->move($destinationPath,$fileName);
	        }
			$optionC = $fileName;
		}

		if (isset($request->option_type_D) && $request->option_type_D == 'text') {
			$optionD_type = $request->option_type_D;
			$optionD = $request->optionD;
		} else {
			$optionD_type = '';
			$optionD = '';
		}

		if (isset($request->option_type_D) && $request->option_type_D == 'image') {
			$optionD_type = $request->option_type_D;
			if ($request->hasFile('optionD_file')) {
	            $file = $request->file('optionD_file');
	        	$fileName = time().'_'.$file->getClientOriginalName();
	        
	            //thumb destination path
	            $destinationPath_2 = public_path().'/upload/answers_file/resize/';
	            $img = Image::make($file->getRealPath());
	            $img->resize(175, 175, function ($constraint) {
	            	$constraint->aspectRatio();
	            })->save($destinationPath_2.'/'.$fileName);
	            //original destination path
	            $destinationPath = public_path().'/upload/answers_file/original/';
	            $file->move($destinationPath,$fileName);
	        }
			$optionD = $fileName;
		}

		if (isset($request->option_type_E) && $request->option_type_E == 'text') {
			$optionE_type = $request->option_type_E;
			$optionE = $request->optionE;
		} else {
			$optionE_type = '';
			$optionE = '';
		}

		if (isset($request->option_type_E) && $request->option_type_E == 'image') {
			$optionE_type = $request->option_type_E;

			if ($request->hasFile('optionE_file')) {
	            $file = $request->file('optionE_file');
	        	$fileName = time().'_'.$file->getClientOriginalName();
	        
	            //thumb destination path
	            $destinationPath_2 = public_path().'/upload/answers_file/resize/';
	            $img = Image::make($file->getRealPath());
	            $img->resize(175, 175, function ($constraint) {
	            	$constraint->aspectRatio();
	            })->save($destinationPath_2.'/'.$fileName);
	            //original destination path
	            $destinationPath = public_path().'/upload/answers_file/original/';
	            $file->move($destinationPath,$fileName);
	        }
			$optionE = $fileName;
		}

    	$tempArray = array(
    		'optionA_type' => $optionA_type,
    		'optionA' => $optionA,
    		'optionB_type' => $optionB_type,
    		'optionB' => $optionB,
    		'optionC_type' => $optionC_type,
    		'optionC' => $optionC,
    		'optionD_type' => $optionD_type,
    		'optionD' => $optionD,
    		'optionE_type' => $optionE_type,
    		'optionE' => $optionE
    	);

    	if ($request->hasFile('explanation_file')) {
            $file = $request->file('explanation_file');
        	$explanation_file_name = time().'_'.$file->getClientOriginalName();
        
            //thumb destination path
            // $destinationPath_2 = public_path().'/upload/explanation_file/resize/';
            // $img = Image::make($file->getRealPath());
            // $img->resize(175, 175, function ($constraint) {
			// $constraint->aspectRatio();
            // })->save($destinationPath_2.'/'.$explanation_file_name);
            //original destination path
            $destinationPath = public_path().'/upload/explanation_file/original/';
            $file->move($destinationPath,$explanation_file_name);
        }else{
            $explanation_file_name = "";
        }

    	$exam_ids = $request->exam;
    	$new_ids = implode(",",$exam_ids);

    	$add = new QuestionAnswer();
    	$add->subject_id = $request->subject;
    	$add->exam_id = $new_ids;
    	$add->area_id = $request->area;
    	$add->section_id = $request->section;
    	$add->level = $request->level;
    	
    	if ($request->question_type == 'text') {
    		$add->question_type = $request->question_type;
    		$add->question = $request->question;
    	}
    	if ($request->question_type == 'image') {
    		$add->question_type = $request->question_type;
    		$add->question = $fileName1;
    	}
    	$add->option_type = $request->option_type;
    	$add->answer = serialize($tempArray);
    	$add->correct_answer = serialize($request->answer);
    	$add->numeric_answer = $request->numeric_correct_ans;
    	$add->status = 1;
    	$add->explanation_details = $request->explanation_details;
    	$add->explanation_file = $explanation_file_name;

    	if ($add->save()) {
    		$request->session()->flash("submit-status", "Question added successfully.");
            return redirect('/question');
    	}
    }

    public function edit(Request $request, $question_id) {
    	$fetch_question_details = QuestionAnswer::find($question_id)->toArray();

    	$fetch_all_subject = Subject::where('status','1')->pluck('sub_full_name','id')->toArray();
    	
    	if($fetch_question_details['option_type'] != 'numeric'){
    		$option = unserialize($fetch_question_details['answer']);
    		$correct_answer = unserialize($fetch_question_details['correct_answer']);
    	}else{
    		$option = '';
    		$correct_answer = '';
    	}

    	$fetch_exam = Exam::where('status','1')->pluck('name','id')->toArray();
    	$fetch_area = Area::where('status','1')->pluck('name','id')->toArray();
    	$fetch_section = Section::pluck('name','id')->toArray();
    	$exam_ids = explode(",", $fetch_question_details['exam_id']);

    	return view('frontend.qustion.edit')->with('fetch_question_details',$fetch_question_details)
    										->with('option',$option)
    										->with('correct_answer',$correct_answer)
    										->with('fetch_all_subject', array_unique($fetch_all_subject))
    										->with('fetch_exam',$fetch_exam)
    										->with('fetch_area',$fetch_area)
    										->with('fetch_section',$fetch_section)
    										->with('exam_ids',$exam_ids);
    }

    public function delete(Request $request, $question_id) {
    	$fetch_question = QuestionAnswer::find($question_id);
    	$fetch_question->status = 5;
    	if ($fetch_question->save()) {
    		$request->session()->flash("submit-status", "Question deleted successfully.");
            return redirect('/question');
    	}
    }

    public function edit_submit(Request $request, $question_id) {
    	Validator::make($request->all(),[
    		'subject' => 'required',
    		'exam' => 'required',
    		'area' => 'required',
    		'section' => 'required',
    		'level' => 'required',
    		'question_type' => 'required|in:text,image',
    		'option_type'=> 'required|in:mcq,numeric',
    		'explanation_details' => 'required' ,
    		'explanation_file' => 'mimetypes:image/jpeg,image/png,image/jpg,video/mp4,application/zip,application/pdf|max:6144' 
    	],[
    		'subject.required' => 'Please select subject.',
    		'exam.required' => 'Please select exam type.',
    		'area.required' => 'Please select area.',
    		'section.required' => 'Please select section.',
    		'level.required' => 'Please select question level.',
    		'question_type.required' => 'Please select question type.',
    		'option_type.required' => 'Please select option type.',
    		'explanation_file.*.mimetypes' => 'Please upload correct file.',
    		'explanation_file.*.max' => 'Please upload file within 6MB'
    	])->validate();

    	if ($request->hasFile('question_image')) {
            $file = $request->file('question_image');
        	$fileName1 = time().'_'.$file->getClientOriginalName();
        
            //thumb destination path
            $destinationPath_2 = public_path().'/upload/question_file/resize/';
            $img = Image::make($file->getRealPath());
            $img->resize(175, 175, function ($constraint) {
            	$constraint->aspectRatio();
            })->save($destinationPath_2.'/'.$fileName1);
            //original destination path
            $destinationPath = public_path().'/upload/question_file/original/';
            $file->move($destinationPath,$fileName1);
        } else {
        	$fileName1 = $request->exit_question_image;
        }

    	if (isset($request->option_type_A) && $request->option_type_A == 'text') {
			$optionA_type = $request->option_type_A;
			$optionA = $request->optionA;
		} else {
			$optionA_type = '';
			$optionA = '';
		}

		if (isset($request->option_type_A) && $request->option_type_A == 'image') {
			$optionA_type = $request->option_type_A;
			if ($request->hasFile('optionA_file')) {
	            $file = $request->file('optionA_file');
	        	$fileName = time().'_'.$file->getClientOriginalName();
	        
	            //thumb destination path
	            $destinationPath_2 = public_path().'/upload/answers_file/resize/';
	            $img = Image::make($file->getRealPath());
	            $img->resize(175, 175, function ($constraint) {
	            	$constraint->aspectRatio();
	            })->save($destinationPath_2.'/'.$fileName);
	            //original destination path
	            $destinationPath = public_path().'/upload/answers_file/original/';
	            $file->move($destinationPath,$fileName);
	        } else {
	        	$fileName = $request->exit_optionA_image;
	        }
			$optionA = $fileName;
		}

		if (isset($request->option_type_B) && $request->option_type_B == 'text') {
			$optionB_type = $request->option_type_B;
			$optionB = $request->optionB;
		} else {
			$optionB_type = '';
			$optionB = '';
		}

		if (isset($request->option_type_B) && $request->option_type_B == 'image') {
			$optionB_type = $request->option_type_B;
			if ($request->hasFile('optionB_file')) {
	            $file = $request->file('optionB_file');
	        	$fileName = time().'_'.$file->getClientOriginalName();
	        
	            //thumb destination path
	            $destinationPath_2 = public_path().'/upload/answers_file/resize/';
	            $img = Image::make($file->getRealPath());
	            $img->resize(175, 175, function ($constraint) {
	            	$constraint->aspectRatio();
	            })->save($destinationPath_2.'/'.$fileName);
	            //original destination path
	            $destinationPath = public_path().'/upload/answers_file/original/';
	            $file->move($destinationPath,$fileName);
	        } else {
	        	$fileName = $request->exit_optionB_image;
	        }
			$optionB = $fileName;
		}

		if (isset($request->option_type_C) && $request->option_type_C == 'text') {
			$optionC_type = $request->option_type_C;
			$optionC = $request->optionC;
		} else {
			$optionC_type = '';
			$optionC = '';
		}

		if (isset($request->option_type_C) && $request->option_type_C == 'image') {
			$optionC_type = $request->option_type_C;

			if ($request->hasFile('optionC_file')) {
	            $file = $request->file('optionC_file');
	        	$fileName = time().'_'.$file->getClientOriginalName();
	        
	            //thumb destination path
	            $destinationPath_2 = public_path().'/upload/answers_file/resize/';
	            $img = Image::make($file->getRealPath());
	            $img->resize(175, 175, function ($constraint) {
	            	$constraint->aspectRatio();
	            })->save($destinationPath_2.'/'.$fileName);
	            //original destination path
	            $destinationPath = public_path().'/upload/answers_file/original/';
	            $file->move($destinationPath,$fileName);
	        } else {
	        	$fileName = $request->exit_optionC_image;
	        }
			$optionC = $fileName;
		}

		if (isset($request->option_type_D) && $request->option_type_D == 'text') {
			$optionD_type = $request->option_type_D;
			$optionD = $request->optionD;
		} else {
			$optionD_type = '';
			$optionD = '';
		}

		if (isset($request->option_type_D) && $request->option_type_D == 'image') {
			$optionD_type = $request->option_type_D;
			if ($request->hasFile('optionD_file')) {
	            $file = $request->file('optionD_file');
	        	$fileName = time().'_'.$file->getClientOriginalName();
	        
	            //thumb destination path
	            $destinationPath_2 = public_path().'/upload/answers_file/resize/';
	            $img = Image::make($file->getRealPath());
	            $img->resize(175, 175, function ($constraint) {
	            	$constraint->aspectRatio();
	            })->save($destinationPath_2.'/'.$fileName);
	            //original destination path
	            $destinationPath = public_path().'/upload/answers_file/original/';
	            $file->move($destinationPath,$fileName);
	        } else {
	        	$fileName = $request->exit_optionD_image;
	        }
			$optionD = $fileName;
		}

		if (isset($request->option_type_E) && $request->option_type_E == 'text') {
			$optionE_type = $request->option_type_E;
			$optionE = $request->optionE;
		} else {
			$optionE_type = '';
			$optionE = '';
		}

		if (isset($request->option_type_E) && $request->option_type_E == 'image') {
			$optionE_type = $request->option_type_E;
			if ($request->hasFile('optionE_file')) {
	            $file = $request->file('optionE_file');
	        	$fileName = time().'_'.$file->getClientOriginalName();
	        
	            //thumb destination path
	            $destinationPath_2 = public_path().'/upload/answers_file/resize/';
	            $img = Image::make($file->getRealPath());
	            $img->resize(175, 175, function ($constraint) {
	            	$constraint->aspectRatio();
	            })->save($destinationPath_2.'/'.$fileName);
	            //original destination path
	            $destinationPath = public_path().'/upload/answers_file/original/';
	            $file->move($destinationPath,$fileName);
	        } else {
	        	$fileName = $request->exit_optionE_image;
	        }
			$optionE = $fileName;
		}

    	$tempArray = array(
    		'optionA_type' => $optionA_type,
    		'optionA' => $optionA,
    		'optionB_type' => $optionB_type,
    		'optionB' => $optionB,
    		'optionC_type' => $optionC_type,
    		'optionC' => $optionC,
    		'optionD_type' => $optionD_type,
    		'optionD' => $optionD,
    		'optionE_type' => $optionE_type,
    		'optionE' => $optionE
    	);

    	if ($request->hasFile('explanation_file')) {
            $file = $request->file('explanation_file');
        	$explanation_file_name = time().'_'.$file->getClientOriginalName();
        
            //thumb destination path
            // $destinationPath_2 = public_path().'/upload/explanation_file/resize/';
            // $img = Image::make($file->getRealPath());
            // $img->resize(175, 175, function ($constraint) {
        	// $constraint->aspectRatio();
            // })->save($destinationPath_2.'/'.$explanation_file_name);
            //original destination path
            $destinationPath = public_path().'/upload/explanation_file/original/';
            $file->move($destinationPath,$explanation_file_name);
        } else {
        	$explanation_file_name = $request->exit_explanation_image;
        }

    	$exam_ids = $request->exam;
    	$new_ids = implode(",",$exam_ids);

    	$edit = QuestionAnswer::find($question_id);
    	$edit->subject_id = $request->subject;
    	$edit->exam_id = $new_ids;
    	$edit->area_id = $request->area;
    	$edit->section_id = $request->section;
    	$edit->level = $request->level;
    	$edit->explanation_details = $request->explanation_details;
    	$edit->explanation_file = $explanation_file_name;
    	
    	if ($request->question_type == 'text') {
    		$edit->question_type = $request->question_type;
    		$edit->question = $request->question;
    	}
    	if ($request->question_type == 'image') {
    		$edit->question_type = $request->question_type;
    		$edit->question = $fileName1;
    	}
    	$edit->option_type = $request->option_type;
    	if ($request->option_type == 'mcq') {
    		$edit->answer = serialize($tempArray);
    		$edit->correct_answer = serialize($request->answer);
    		$edit->numeric_answer = '';
    	}
    	if ($request->option_type == 'numeric') {
    		$edit->answer = '';
	    	$edit->correct_answer = '';
	    	$edit->numeric_answer = $request->numeric_correct_ans;
    	}
    	
    	if ($edit->save()) {
    		$request->session()->flash("submit-status", "Question edit successfully.");
            return redirect('/question');
    	}
    }

    public function search() {
    	$fetch_all_subject = Subject::where('status','1')->pluck('sub_full_name', 'id')->toArray();
    	$fetch_all_exam = Exam::where('status','1')->pluck('name', 'id')->toArray();
    	$fetch_all_area = Area::where('status','1')->pluck('name', 'id')->toArray();
    	$fetch_all_section = Section::pluck('name', 'id')->toArray();
    	return view('frontend.qustion.search')->with('fetch_all_subject', $fetch_all_subject)->with('fetch_all_exam', $fetch_all_exam)->with('fetch_all_area', $fetch_all_area)->with('fetch_all_section', $fetch_all_section);
    }

    public function filter_submit(Request $request) {
    	$output = array();
    	$question = DB::table('question_answers')
    				->where('status','=','1')
    				->when($request->subject, function($query) use ($request) {
    					return $query->where('subject_id', $request->subject);
    				})
    				->when($request->exam, function($query) use ($request) {
    					return $query->whereIn('exam_id', array($request->exam));
    				})
    				->when($request->section, function($query) use ($request) {
    					return $query->where('section_id', $request->section);
    				})
    				->when($request->area, function($query) use ($request) {
    					return $query->where('area_id', $request->area);
    				})
    				->when($request->level, function($query) use ($request) {
    					return $query->where('level', $request->level);
    				})
    				->get()->toArray();

    	foreach ($question as $key => $value) {
    		$new_question = (array) $value;
    		$fetch_subject_details = Subject::find($value->subject_id)->toArray();
    		$new_question['subject']['sub_full_name'] = $fetch_subject_details['sub_full_name'];
    		$fetch_area_details = Area::find($value->area_id)->toArray();
    		$new_question['area']['name'] = $fetch_area_details['name'];
    		$fetch_section_details = Section::find($value->section_id)->toArray();
    		$new_question['section']['name'] = $fetch_section_details['name'];
    		$fetch_exam_details = Exam::find($value->exam_id)->toArray();
    		$new_question['exam'] = $fetch_exam_details['name'];
    		array_push($output, $new_question);
        }
        $fetch_all_subject = Subject::where('status','1')->pluck('sub_full_name', 'id')->toArray();
        $fetch_all_exam = Exam::where('status','1')->pluck('name', 'id')->toArray();
        $fetch_all_area = Area::where('status','1')->pluck('name', 'id')->toArray();
        $fetch_all_section = Section::pluck('name', 'id')->toArray();
        return view ('frontend.qustion.listings')->with('fetch_all_question', $output)->with('fetch_all_subject', $fetch_all_subject)->with('fetch_all_exam', $fetch_all_exam)->with('fetch_all_area', $fetch_all_area)->with('fetch_all_section', $fetch_all_section)->with('subject', $request->subject)->with('exam', $request->exam)->with('section', $request->section)->with('area', $request->area)->with('level', $request->level);
    }
}

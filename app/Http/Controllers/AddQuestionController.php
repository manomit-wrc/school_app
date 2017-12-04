<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Subject;
use App\Exam;
use App\Area;
use App\Section;
use Validator;
use App\QuestionAnswer;
use Image;

class AddQuestionController extends Controller
{
    public function index () {
    	$fetch_all_question = QuestionAnswer::with('subject','exams')->where('status','1')->orderby('id','desc')->get()->toArray();

    	return view ('frontend.qustion.listings')->with('fetch_all_question',$fetch_all_question);
    }

    public function add_qustion_view () {
    	$fetch_all_subject = Subject::where('status','1')->pluck('sub_full_name','id')->toArray();
    	return view ('frontend.qustion.add')->with('fetch_all_subject',$fetch_all_subject);
    }

    public function fetch_exam_subject_wise (Request $request) {
    	$tempArray = array();
    	$subject_id = $request->subject_id;
    	$fetch_exam_id = Subject::where([['status','1'],['id',$subject_id]])->get()->toArray();
    	foreach($fetch_exam_id as $key => $value){
    		$exam_id = $value['exam_id'];
    		$fetch_exam_details = Exam::where('id',$exam_id)->get()->toArray();

    		$exam_details_array['exam_id'] = $fetch_exam_details[0]['id'];
    		$exam_details_array['exam_name'] = $fetch_exam_details[0]['name'];

    		$tempArray[] = $exam_details_array;
    	}

    	return response()->json(['tempArray'=>$tempArray]);
    }

    public function fetch_area_exam_wise (Request $request) {
    	$tempArray = array();

    	$exam_id = $request->exam_id;
    	$subject_id = $request->subject_id;

    	$fetch_area = Area::where([['exam_id',$exam_id],['subject_id',$subject_id],['status','1']])->get()->toArray();

    	return response()->json(['fetch_area'=>$fetch_area]);
    }

    public function fetch_section_area_wise (Request $request) {
    	$area_id = $request->area_id;

    	$fetch_section_details = Section::where('area_id',$area_id)->get()->toArray();
    	return response()->json(['fetch_section_details'=>$fetch_section_details]);
    }

    public function add_qustion_submit (Request $request) {
    	Validator::make($request->all(),[
    		'subject' => 'required',
    		'exam' => 'required',
    		'area' => 'required',
    		'section' => 'required',
    		'level' => 'required',
    		'answer' => 'required'
    	],[
    		'subject.required' => 'Please select subject.',
    		'exam.required' => 'Please select exam type.',
    		'area.required' => 'Please select area.',
    		'section.required' => 'Please select section.',
    		'level.required' => 'Please select question level.',
    		'answer.required' => 'Please select correct answer.'
    	])->validate();

    	if ($request->hasFile('question_image')) {
            $file = $request->file('question_image');
        	$fileName = time().'_'.$file->getClientOriginalName();
        
            //thumb destination path
            $destinationPath_2 = public_path().'/upload/question_file/resize/';
            $img = Image::make($file->getRealPath());
            $img->resize(175, 175, function ($constraint) {
              $constraint->aspectRatio();
            })->save($destinationPath_2.'/'.$fileName);
            //original destination path
            $destinationPath = public_path().'/upload/question_file/original/';
            $file->move($destinationPath,$fileName);
        }

    	if(isset($request->option_type_A) && $request->option_type_A == 'text'){
			$optionA_type = $request->option_type_A;
			$optionA = $request->option;
		}else{
			$optionA_type = '';
			$optionA = '';
		}

		if(isset($request->option_type_A) && $request->option_type_A == 'image'){
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
		}else{
			$optionA_type = '';
			$optionA = '';
		}

		if(isset($request->option_type_B) && $request->option_type_B == 'text'){
			$optionB_type = $request->option_type_B;
			$optionB = $request->optionB;
		}else{
			$optionB_type = '';
			$optionB = '';
		}

		if(isset($request->option_type_B) && $request->option_type_B == 'image'){
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
		}else{
			$optionB_type = '';
			$optionB = '';
		}

		if(isset($request->option_type_C) && $request->option_type_C == 'text'){
			$optionC_type = $request->option_type_C;
			$optionC = $request->optionC;
		}else{
			$optionC_type = '';
			$optionC = '';
		}

		if(isset($request->option_type_C) && $request->option_type_C == 'image'){
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
		}else{
			$optionC_type = '';
			$optionC = '';
		}


		if(isset($request->option_type_D) && $request->option_type_D == 'text'){
			$optionD_type = $request->option_type_D;
			$optionD = $request->optionD;
		}else{
			$optionD_type = '';
			$optionD = '';
		}

		if(isset($request->option_type_D) && $request->option_type_D == 'image'){
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
		}else{
			$optionD_type = '';
			$optionD = '';
		}

		if(isset($request->option_type_E) && $request->option_type_E == 'text'){
			$optionE_type = $request->option_type_E;
			$optionE = $request->optionE;
		}else{
			$optionE_type = '';
			$optionE = '';
		}

		if(isset($request->option_type_E) && $request->option_type_E == 'image'){
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
		}else{
			$optionE_type = '';
			$optionE = '';
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

    	$add = new QuestionAnswer();
    	$add->subject_id = $request->subject;
    	$add->exam_id = $request->exam;
    	$add->area_id = $request->area;
    	$add->section_id = $request->section;
    	$add->level = $request->level;
    	
    	if($request->question_type == 'text'){
    		$add->question_type = $request->question_type;
    		$add->question = $request->question;
    	}
    	if($request->question_type == 'image'){
    		$add->question_type = $request->question_type;
    		$add->question = $fileName;
    	}

    	$add->answer = serialize($tempArray);
    	$add->correct_answer = serialize($request->answer);
    	$add->status = 1;

    	if($add->save()){
    		$request->session()->flash("submit-status", "Question added successfully.");
            return redirect('/question');
    	}
    }

    public function edit (Request $request,$question_id) {
    	$fetch_question_details = QuestionAnswer::find($question_id)->toArray();
    	$fetch_all_subject = Subject::where('status','1')->pluck('sub_full_name','id')->toArray();
    	$option = unserialize($fetch_question_details['answer']);
    	$correct_answer = unserialize($fetch_question_details['correct_answer']);
    	// echo "<pre>";
    	// print_r($fetch_question_details);
    	// print_r($option);
    	// print_r($correct_answer);
    	// die();
    	return view('frontend.qustion.edit')->with('fetch_question_details',$fetch_question_details)
    										->with('option',$option)
    										->with('correct_answer',$correct_answer)
    										->with('fetch_all_subject', $fetch_all_subject);
    }

    public function delete (Request $request,$question_id) {
    	$fetch_question = QuestionAnswer::find($question_id);
    	$fetch_question->status = 5;

    	if($fetch_question->save()){
    		$request->session()->flash("submit-status", "Question deleted successfully.");
            return redirect('/question');
    	}
    }
}

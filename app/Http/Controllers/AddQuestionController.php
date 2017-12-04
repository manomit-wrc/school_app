<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Subject;
use App\Exam;
use App\Area;
use App\Section;
use Validator;
use App\QuestionAnswer;

class AddQuestionController extends Controller
{
    public function index () {
    	return view ('frontend.qustion.listings');
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
    		'question' => 'required',
    		'answer' => 'required'
    	],[
    		'subject.required' => 'Please select subject.',
    		'exam.required' => 'Please select exam type.',
    		'area.required' => 'Please select area.',
    		'section.required' => 'Please select section.',
    		'level.required' => 'Please select question level.',
    		'question.required' => 'Please write question.',
    		'answer.required' => 'Please select correct answer.'
    	])->validate();

    	$add = new QuestionAnswer();
    	$add->subject_id = $request->subject;
    	$add->exam_id = $request->exam;
    	$add->area_id = $request->area;
    	$add->section_id = $request->section;
    	$add->level = $request->level;

    }
}

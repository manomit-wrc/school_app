<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exam;
use App\Tag;
use App\ExamTimer;
use Validator;

class ExamController extends Controller
{
    public function index(Request $request) {
    	$exam_details = Exam::where('status', '1')->orderBy('name', 'asc')->get();
    	return view('frontend.exam.index')->with('exam_details', $exam_details);
    }

    public function add(Request $request) {
    	$all_tags = Tag::where('status', '1')->get()->toArray();
    	return view('frontend.exam.add')->with('all_tags', $all_tags);	
    }

    public function save(Request $request) {
    	Validator::make($request->all(),[
    		'code' => 'required|unique:exams,code',
    		'name' => 'required|unique:exams,name',
    		'description' => 'required'
    	],[
    		'code.required' => 'Please enter exam code',
    		'code.unique' => 'Exam code already taken',
    		'name.required' => 'Please enter exam name',
    		'name.unique' => 'Exam name already taken',
    		'description.required' => 'Please enter exam description'
    	])->validate();

    	$exam = new Exam();
    	$exam->code = $request->code;
    	$exam->name = $request->name;
    	$exam->description = $request->description;

    	if ($exam->save()) {
    		if (count($request->tags) > 0) {
                $exam->tags()->attach($request->tags);
            }
        	$request->session()->flash("submit-status", "Exam added successfully.");
            return redirect('/exam');
    	}
    }

    public function edit(Request $request, $id) {
    	$exam = Exam::find($id);
        $tags_array = array();
        $all_tags = Tag::where('status', '1')->get()->toArray();

        $exam_tags = Exam::with('tags')->where('id', $id)->get()->toArray();
        foreach ($exam_tags[0]['tags'] as $key => $value) {
            $tags_array[] = $value['id'];
        }
        return view('frontend.exam.edit')->with(['all_tags' => $all_tags, 'tags_array' => $tags_array, 'exam' => $exam]);
    }

    public function update(Request $request, $id) {
    	$exam = Exam::find($id);
    	Validator::make($request->all(),[
    		'code' => 'required|unique:exams,code,'.$id,
    		'name' => 'required|unique:exams,name,'.$id,
    		'description' => 'required'
    	],[
    		'code.required' => 'Please enter exam code',
    		'code.unique' => 'Exam code already taken',
    		'name.required' => 'Please enter exam name',
    		'name.unique' => 'Exam name already taken',
    		'description.required' => 'Please enter exam description'
    	])->validate();

    	$exam->code = $request->code;
    	$exam->name = $request->name;
    	$exam->description = $request->description;
    	if ($exam->save()) {
            if (count($request->tags) > 0) {
               $exam->tags()->wherePivot('exam_id', '=', $id)->detach();
               $exam->tags()->attach($request->tags);
            }
        	$request->session()->flash("submit-status", "Exam updated successfully.");
            return redirect('/exam');
        }
    }

    public function delete(Request $request, $id) {
    	$exam = Exam::find($id);
    	if ($exam->delete()) {
    		$exam->tags()->wherePivot('exam_id', '=', $id)->detach();
    		$request->session()->flash("submit-status", "Exam deleted successfully.");
            return redirect('/exam');
    	}
    }

    public function view_timer() {
        $exam_timer = ExamTimer::all()->toArray();
        return view('frontend.exam.edit_timer')->with('exam_timer', $exam_timer);
    }

    public function update_exam_timer(Request $request) {
        $exist_timer = ExamTimer::first();
        if (!$exist_timer) {
            $timer = new ExamTimer;
        } else {
            $timer = ExamTimer::first();
        }
        $timer->section_test = $request->section_test;
        $timer->area_test = $request->area_test;
        $timer->subject_test = $request->subject_test;
        $timer->exam_test = $request->exam_test;
        if ($timer->save()) {
            $request->session()->flash("submit-status", "Timer Settings updated successfully.");
            return redirect('/exam_timer');
        } else {
            $request->session()->flash("error-status", "Timer Settings updation failed.");
            return redirect('/exam_timer');
        }
    }

}

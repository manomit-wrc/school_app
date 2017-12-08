<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exam;
use App\Tag;
use Validator;

class ExamController extends Controller
{
    public function index(Request $request) {
    	$exam_details = Exam::all();
    	return view('frontend.exam.index')->with('exam_details',$exam_details);
    }

    public function add(Request $request) {
    	$all_tags = Tag::where('status','1')->get()->toArray();
    	return view('frontend.exam.add')->with('all_tags',$all_tags);	
    }

    public function save(Request $request) {
    	Validator::make($request->all(),[
    		'code' => 'required|unique:exams,code',
    		'name' => 'required|unique:exams,name',
    		'description' => 'required',
    		'start_date' => 'required|date',
    		'end_date' => 'required|date|after_or_equal:start_date'
    	],[
    		'code.required' => 'Please enter exam code.',
    		'code.unique' => 'Exam code already taken',
    		'name.required' => 'Please enter exam name.',
    		'name.unique' => 'Exam name already taken',
    		'description.required' => 'Please enter exam description.',
    		'start_date.required' => 'Please select exam start date.',
    		'start_date.date' => 'Must be date type',
    		'end_date.required' => 'Please select exam end date.',
    		'end_date.date' => 'Must be date type',
    		'end_date.after_or_equal' => 'Start date can not be grater than end date'
    	])->validate();

    	$exam = new Exam();
    	$exam->code = $request->code;
    	$exam->name = $request->name;
    	$exam->description = $request->description;
    	$exam->start_date = \Carbon\Carbon::parse($request->start_date);
    	$exam->end_date = \Carbon\Carbon::parse($request->end_date);

    	if($exam->save()) {
    		if(count($request->tags) > 0) {
                $exam->tags()->attach($request->tags);
            }
        	$request->session()->flash("submit-status", "Exam added successfully.");
            return redirect('/exam');
    	}
    }

    public function edit(Request $request, $id) {
    	$exam = Exam::find($id);
        $tags_array = array();
        $all_tags = Tag::where('status','1')->get()->toArray();

        $exam_tags = Exam::with('tags')->where('id',$id)->get()->toArray();
        

        foreach ($exam_tags[0]['tags'] as $key => $value) {
          $tags_array[] = $value['id'];
        }
        return view('frontend.exam.edit')->with(['all_tags'=>$all_tags,'tags_array'=>$tags_array,'exam'=>$exam]);
    }

    public function update(Request $request, $id) {
    	$exam = Exam::find($id);
    	Validator::make($request->all(),[
    		'code' => 'required|unique:exams,code,'.$id,
    		'name' => 'required|unique:exams,name,'.$id,
    		'description' => 'required',
    		'start_date' => 'required|date',
    		'end_date' => 'required|date|after_or_equal:start_date'
    	],[
    		'code.required' => 'Please enter exam code.',
    		'code.unique' => 'Exam code already taken',
    		'name.required' => 'Please enter exam name.',
    		'name.unique' => 'Exam name already taken',
    		'description.required' => 'Please enter exam description.',
    		'start_date.required' => 'Please select exam start date.',
    		'start_date.date' => 'Must be date type',
    		'end_date.required' => 'Please select exam end date.',
    		'end_date.date' => 'Must be date type',
    		'end_date.after_or_equal' => 'Start date can not be grater than end date'
    	])->validate();

    	$exam->code = $request->code;
    	$exam->name = $request->name;
    	$exam->description = $request->description;
    	$exam->start_date = \Carbon\Carbon::parse($request->start_date);
    	$exam->end_date = \Carbon\Carbon::parse($request->end_date);

    	if($exam->save()){
            if(count($request->tags) > 0) {
               $exam->tags()->wherePivot('exam_id', '=', $id)->detach();
               $exam->tags()->attach($request->tags);
            }
            

        	$request->session()->flash("submit-status", "Exam edited successfully.");
            return redirect('/exam');
        }
    }

    public function delete(Request $request, $id) {
    	$exam = Exam::find($id);
    	if($exam->delete()) {
    		$exam->tags()->wherePivot('exam_id', '=', $id)->detach();
    		$request->session()->flash("submit-status", "Exam deleted successfully.");
            return redirect('/exam');
    	}
    }

    public function get_all_exam(Request $request) {
        $exam_list = Exam::all();
        return response()->json(['msg' => 'Success', 'status_code' => 200, 'data' => $exam_list]);
    }

}

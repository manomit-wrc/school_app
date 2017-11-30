<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Area;
use App\Exam;
use App\Subject;
use Validator;


class AreaController extends Controller
{
    public function index() {
    	$areas = Area::with('subjects.exams')->get()->toArray();

    	return view('frontend.area.index')->with('areas', $areas);
    }

    public function add() {
    	$exams = Exam::where('status','1')->pluck('name','id');
    	return view('frontend.area.add')->with('exams',$exams);
    }
    public function save(Request $request) {
    	Validator::make($request->all(),[
    		'exam_id' => 'required',
    		'subject_id' => 'required',
    		'code' => 'required|unique:areas,code',
    		'name' => 'required|unique:areas,name',
    		'description' => 'required'
		],[
			'exam_id.required' => 'Please select exam',
			'subject_id.required' => 'Please select subject',
			'code.required' => 'Please enter area code',
			'code.unique' => 'Code already taken',
			'name.required' => 'Please enter area name',
			'name.unique' => 'Name already taken',
			'description.required' => 'Please enter description'
		])->validate();

		Area::create($request->all());
		$request->session()->flash("submit-status",'Area added successfully.');
    	return redirect('/area');
    }

    public function edit($id) {
    	$area = Area::find($id);
    	$exams = Exam::where('status','1')->pluck('name','id');
    	$subjects = Subject::where('exam_id',$area->exam_id)->pluck('sub_short_name','id');

    	return view('frontend.area.edit')->with(['area' => $area,'exams' => $exams, 'subjects' => $subjects]);
    }

    public function update(Request $request, $id) {
    	Validator::make($request->all(),[
    		'exam_id' => 'required',
    		'subject_id' => 'required',
    		'code' => 'required|unique:areas,code,'.$id,
    		'name' => 'required|unique:areas,name,'.$id,
    		'description' => 'required'
		],[
			'exam_id.required' => 'Please select exam',
			'subject_id.required' => 'Please select subject',
			'code.required' => 'Please enter area code',
			'code.unique' => 'Code already taken',
			'name.required' => 'Please enter area name',
			'name.unique' => 'Name already taken',
			'description.required' => 'Please enter description'
		])->validate();

		$area = Area::find($id);
		if($area) {
			$area->exam_id = $request->exam_id;
			$area->subject_id = $request->subject_id;
			$area->code = $request->code;
			$area->name = $request->name;
			$area->description = $request->description;

			if($area->save()) {
				$request->session()->flash("submit-status",'Area updated successfully.');
    			return redirect('/area');
			}

		}
		else {
			$request->session()->flash("submit-status",'Please try again.');
    		return redirect('/area');
		}
    }

    public function delete(Request $request, $id) {
    	$area = Area::find($id);
    	if($area->delete()) {
    		$request->session()->flash("submit-status",'Area deleted successfully.');
    		return redirect('/area');
    	}
    }

    public function get_subject_by_exam(Request $request) {
    	if($request->ajax()){
            $subjects = Subject::select('id','sub_short_name')->where('exam_id',$request->exam_id)->get()->toArray();
            return response()->json(['subjects' => $subjects]);
        }
    }
}

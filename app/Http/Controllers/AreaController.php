<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Area;
use App\Exam;
use App\Subject;
use App\SubjectExam;
use Validator;

class AreaController extends Controller
{
    public function index() {
    	$areas = Area::with('subjects')->get()->toArray();
        foreach ($areas as $key => $value) {
            $exam_details = Exam::find($value['exam_id'])->toArray();
            $areas[$key]['exam'] = $exam_details['code'];
        }
    	return view('frontend.area.index')->with('areas', $areas);
    }

    public function add() {
    	$fetch_all_subject = Subject::where('status', '1')->pluck('sub_full_name', 'id')->toArray();
    	return view('frontend.area.add')->with('fetch_all_subject',$fetch_all_subject);
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
		$request->session()->flash("submit-status", 'Area added successfully.');
    	return redirect('/area');
    }

    public function edit($id) {
    	$area = Area::find($id);
        $area_details = $area->toArray();
        $exam_details_arr = array();
    	$subjects = Subject::where('status', '1')->pluck('sub_full_name', 'id')->toArray();
        $exams = SubjectExam::where('subject_id', $area['subject_id'])->get()->toArray();
        foreach ($exams as $key => $value) {
            $exam_details = Exam::find($value['exam_id'])->toArray();
            $exam_details_arr[] = array(
                'id' => $exam_details['id'],
                'name' => $exam_details['name']
            );
        }
    	return view('frontend.area.edit')->with(['area' => $area, 'subjects' => $subjects, 'exams' => $exam_details_arr]);
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
		if ($area) {
			$area->exam_id = $request->exam_id;
			$area->subject_id = $request->subject_id;
			$area->code = $request->code;
			$area->name = $request->name;
			$area->description = $request->description;

			if ($area->save()) {
				$request->session()->flash("submit-status",'Area updated successfully.');
    			return redirect('/area');
			}
		} else {
			$request->session()->flash("submit-status",'Please try again.');
    		return redirect('/area');
		}
    }

    public function delete(Request $request, $id) {
    	$area = Area::find($id);
    	if ($area->delete()) {
    		$request->session()->flash("submit-status",'Area deleted successfully.');
    		return redirect('/area');
    	}
    }

    public function get_exam_by_subject(Request $request) {
        $exam_details_arr = array();
    	if ($request->ajax()) {
            $exams = SubjectExam::where('subject_id', $request->subject_id)->get()->toArray();
            foreach ($exams as $key => $value) {
                $exam_details = Exam::find($value['exam_id'])->toArray();
                array_push($exam_details_arr, $exam_details);
            }
            return response()->json(['exams' => $exam_details_arr]);
        }
    }
}

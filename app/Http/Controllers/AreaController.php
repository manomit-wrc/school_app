<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Area;
use App\Exam;
use App\Subject;
use App\SubjectExam;
use Validator;
use DB;

class AreaController extends Controller
{
    public function index() {
    	$areas = Area::with('subjects')->orderBy('name', 'asc')->get()->toArray();
        foreach ($areas as $key => $value) {
            $exam_name = '';
            $exam_ids = explode(',', $value['exam_id']);
            foreach ($exam_ids as $e_id) {
                $exam_details = Exam::find($e_id)->toArray();
                $exam_name .= $exam_details['name'] . ', ';
            }
            $exam_name = rtrim($exam_name, ', ');
            $areas[$key]['exam'] = $exam_name;
        }
        $fetch_all_subject = Subject::where('status', '1')->pluck('sub_full_name', 'id')->toArray();
        $fetch_all_exam = Exam::where('status', '1')->pluck('name', 'id')->toArray();
    	return view('frontend.area.index')->with('areas', $areas)->with('fetch_all_subject', $fetch_all_subject)->with('fetch_all_exam', $fetch_all_exam);
    }

    public function listing($subject_id) {
        $areas = Area::with('subjects')->where('subject_id', $subject_id)->orderBy('name', 'asc')->get()->toArray();
        foreach ($areas as $key => $value) {
            $exam_name = '';
            $exam_ids = explode(',', $value['exam_id']);
            foreach ($exam_ids as $e_id) {
                $exam_details = Exam::find($e_id)->toArray();
                $exam_name .= $exam_details['name'] . ', ';
            }
            $exam_name = rtrim($exam_name, ', ');
            $areas[$key]['exam'] = $exam_name;
        }
        return view('frontend.area.listing')->with('areas', $areas);
    }

    public function add() {
    	$fetch_all_subject = Subject::where('status', '1')->pluck('sub_full_name', 'id')->toArray();
    	return view('frontend.area.add')->with('fetch_all_subject',$fetch_all_subject);
    }
    
    public function save(Request $request) {
    	Validator::make($request->all(),[
    		'exam_id' => 'required',
    		'subject_id' => 'required',
    		'name' => 'required|unique:areas,name',
    		'description' => 'required'
		],[
			'exam_id.required' => 'Please select exam',
			'subject_id.required' => 'Please select subject',
			'name.required' => 'Please enter area name',
			'name.unique' => 'Name already taken',
			'description.required' => 'Please enter description'
		])->validate();

        $exam_ids = $request->exam_id;
        $new_ids = implode(",", $exam_ids);

        $add = new Area();
        $add->subject_id = $request->subject_id;
        $add->exam_id = $new_ids;
        $add->name = $request->name;
        $add->description = $request->description;

        if ($add->save()) {
    		$request->session()->flash("submit-status", 'Area added successfully.');
        	return redirect('/area');
        } else {
            $request->session()->flash("submit-status", 'Area addition failure.');
            return redirect('/area/add');
        }
    }

    public function edit($id) {
    	$area = Area::find($id);
        $area_details = $area->toArray();
        $exam_details_arr = array();
    	$subjects = Subject::where('status', '1')->pluck('sub_full_name', 'id')->toArray();
        $exams = SubjectExam::where('subject_id', $area['subject_id'])->get()->toArray();
        $exam_ids = explode(",", $area_details['exam_id']);
        foreach ($exams as $key => $value) {
            $exam_details = Exam::find($value['exam_id'])->toArray();
            $exam_details_arr[] = array(
                'id' => $exam_details['id'],
                'name' => $exam_details['name']
            );
        }
    	return view('frontend.area.edit')->with(['area' => $area, 'subjects' => $subjects, 'exams' => $exam_details_arr, 'exam_ids' => $exam_ids]);
    }

    public function update(Request $request, $id) {
    	Validator::make($request->all(),[
    		'exam_id' => 'required',
    		'subject_id' => 'required',
    		'name' => 'required|unique:areas,name,'.$id,
    		'description' => 'required'
		],[
			'exam_id.required' => 'Please select exam',
			'subject_id.required' => 'Please select subject',
			'name.required' => 'Please enter area name',
			'name.unique' => 'Name already taken',
			'description.required' => 'Please enter description'
		])->validate();

		$area = Area::find($id);
		if ($area) {
            $exam_ids = $request->exam_id;
            $new_ids = implode(",", $exam_ids);

			$area->exam_id = $new_ids;
			$area->subject_id = $request->subject_id;
			$area->name = $request->name;
			$area->description = $request->description;

			if ($area->save()) {
				$request->session()->flash("submit-status", 'Area updated successfully.');
    			return redirect('/area');
			}
		} else {
			$request->session()->flash("submit-status", 'Please try again.');
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

    public function sort_order_update(Request $request) {
        $area_order = $request->area_order;
        $area_ids = $request->area_id;
        $i = 1;
        $area_arr = array();
        foreach ($area_order as $value) {
            $key = array_search($value, $request->area_order);
            $area_arr[] = array(
                'area' => $area_ids[$key],
                'area_order' => ++$key
            );
        }

        foreach ($area_arr as $value) {
            $area_details = Area::find($value['area']);
            $area_details->sort_order = $value['area_order'];
            $area_details->save();
        }
        return 1;
    }

    public function filter_submit(Request $request) {
        $output = array();
        $area = DB::table('areas')
                ->where('status', '1')
                ->when($request->subject, function($query) use ($request) {
                    return $query->where('subject_id', $request->subject);
                })
                ->when($request->exam, function($query) use ($request) {
                    return $query->where('exam_id', 'like', '%'.$request->exam.'%');
                })
                ->orderBy('sort_order', 'asc')->get()->toArray();         

        foreach ($area as $key => $value) {
            $new_area = (array) $value;
            $fetch_subject_details = Subject::find($value->subject_id)->toArray();
            $new_area['subjects']['sub_full_name'] = $fetch_subject_details['sub_full_name'];

            $exam_name = '';
            $exam_ids = explode(',', $value->exam_id);
            foreach ($exam_ids as $e_id) {
                $fetch_exam_details = Exam::find($e_id)->toArray();
                $exam_name .= $fetch_exam_details['name'] . ', ';
            }
            $exam_name = rtrim($exam_name, ', ');
            $new_area['exam'] = $exam_name;
            
            array_push($output, $new_area);
        }
        $fetch_all_subject = Subject::where('status', '1')->pluck('sub_full_name', 'id')->toArray();
        $fetch_all_exam = Exam::where('status', '1')->pluck('name', 'id')->toArray();
        return view ('frontend.area.index')->with('areas', $output)->with('fetch_all_subject', $fetch_all_subject)->with('fetch_all_exam', $fetch_all_exam)->with('subject', $request->subject)->with('exam', $request->exam);
    }
}

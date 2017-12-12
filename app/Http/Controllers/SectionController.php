<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Area;
use App\Section;
use Validator;

class SectionController extends Controller
{
    public function index($area_id) {
    	$sections = Section::where('area_id', $area_id)->get()->toArray();
    	return view('frontend.section.index')->with(['sections' => $sections, 'area_id' => $area_id]);
    }

    public function add($area_id) {
    	return view('frontend.section.add')->with('area_id',$area_id);
    }

    public function save(Request $request, $area_id) {
    	Validator::make($request->all(),[
    		'code' => 'required|unique:areas,code',
    		'name' => 'required|unique:areas,name',
    		'description' => 'required'
		],[
			'code.required' => 'Please enter area code',
			'code.unique' => 'Code already taken',
			'name.required' => 'Please enter area name',
			'name.unique' => 'Name already taken',
			'description.required' => 'Please enter description'
		])->validate();

		$section = new Section();
		$section->area_id = $area_id;
		$section->code = $request->code;
		$section->name = $request->name;
		$section->description = $request->description;

		if($section->save()) {
			$request->session()->flash("submit-status",'Section added successfully.');
    		return redirect('/area/section/'.$area_id);
		}
    }

    public function edit($area_id, $id) {
    	$section = Section::find($id);
    	return view('frontend.section.edit')->with(['area_id' => $area_id,'section' => $section]);
    }

    public function update(Request $request, $area_id, $id) {
    	Validator::make($request->all(),[
    		'code' => 'required|unique:areas,code,'.$id,
    		'name' => 'required|unique:areas,name,'.$id,
    		'description' => 'required'
		],[
			'code.required' => 'Please enter area code',
			'code.unique' => 'Code already taken',
			'name.required' => 'Please enter area name',
			'name.unique' => 'Name already taken',
			'description.required' => 'Please enter description'
		])->validate();

		$section = Section::find($id);
		$section->area_id = $area_id;
		$section->code = $request->code;
		$section->name = $request->name;
		$section->description = $request->description;

		if($section->save()) {
			$request->session()->flash("submit-status",'Section updated successfully.');
    		return redirect('/area/section/'.$area_id);
		}
    }

    public function delete(Request $request, $area_id, $id) {
    	$section = Section::find($id);
    	if ($section->delete()) {
    		$request->session()->flash("submit-status",'Section deleted successfully.');
    		return redirect('/area/section/'.$area_id);
    	}
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Auth;
use App\Tips;

class TipsController extends Controller
{
    public function index(Request $request) {
    	$all_tips_details = Tips::where('status', '1')->orderby('created_at', 'desc')->get()->toArray();
    	return view('frontend.tips.index')->with('all_tips_details', $all_tips_details);
    }

    public function add(Request $request) {
    	return view('frontend.tips.add');
    }

    public function add_submit(Request $request) {
    	Validator::make($request->all(),[
    		'tips_title' => 'required|unique:tips,tips_title',
    		'tips_desc' => 'required',
    	],[
    		'tips_title.required' => 'Please enter title',
    		'tips_title.unique' => 'Title already exists. Please try another',
    		'tips_desc.required' => 'Please enter description'
    	])->validate();

        $add = new Tips();
        $add->tips_title = $request->tips_title;
        $add->tips_desc = $request->tips_desc;
        $add->status = 1;
        if ($add->save()) {
        	$request->session()->flash("submit-status", "Tips added successfully.");
            return redirect('/tips');
        } else {
        	$request->session()->flash("submit-status", "Tips addition failed.");
            return redirect('/tips/add');
        }
    }

    public function edit(Request $request, $tips_id) {
    	$tips_details = Tips::find($tips_id)->toArray();
    	return view('frontend.tips.edit')->with('tips_details', $tips_details);
    }

    public function edit_submit(Request $request, $tips_id) {
    	Validator::make($request->all(),[
    		'tips_title' => 'required|unique:tips,tips_title'.$tips_id,
    		'tips_desc' => 'required',
    	],[
    		'tips_title.required' => 'Please enter title',
    		'tips_title.unique' => 'Title already exists. Please try another',
    		'tips_desc.required' => 'Please enter description'
    	])->validate();

        $edit = Tips::find($tips_id);
        $edit->tips_title = $request->tips_title;
        $edit->tips_desc = $request->tips_desc;
        if ($edit->save()) {
        	$request->session()->flash("submit-status", "Tips updated successfully.");
            return redirect('/tips');
        } else {
        	$request->session()->flash("submit-status", "Tips updation failed.");
            return redirect('/tips/edit/'.$tips_id);
        }
    }

    public function delete(Request $request, $tips_id) {
    	$delete = Tips::find($tips_id);
    	if ($delete->delete()) {
    		$request->session()->flash("submit-status", "Tips deleted successfully.");
            return redirect('/tips');
    	} else {
    		$request->session()->flash("submit-status", "Tips deletion failed.");
            return redirect('/tips');
    	}
    }
}

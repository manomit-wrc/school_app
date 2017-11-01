<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Tag;

class TagController extends Controller
{
    public function tags_listing () {
    	$fetch_all_tags = Tag::where('status','1')->orderby('id','desc')->get()->toArray();
    	return view('frontend.tags.listing')->with('fetch_all_tags',$fetch_all_tags);
    }

    public function tags_add (){
    	return view('frontend.tags.add');
    }

    public function tags_add_save (Request $request) {
    	Validator::make($request->all(),[
    		'tags_name' => 'required|unique:tags,tag_name'
    	],[
    		'tags_name.required' => 'Please enter tags.',
    		'tags_name.unique' => 'Tag name is already exit.'
    	])->validate();

    	$add = new Tag();
    	$add->tag_name = $request->tags_name;
    	$add->status = 1;

    	if($add->save()){
    		$request->session()->flash("submit-status",'Tags added successfully.');
    		return redirect('/tags');
    	}
    }

    public function tags_edit ($tag_id){
    	$fetch_tag_details = Tag::find($tag_id)->toArray();
    	return view('frontend.tags.edit')->with('fetch_tag_details',$fetch_tag_details);
    }

    public function tags_edit_save (Request $request,$tag_id) {
    	Validator::make($request->all(),[
    		'tags_name' => 'required|unique:tags,tag_name,'.$tag_id
    	],[
    		'tags_name.required' => 'Please enter tags.',
    		'tags_name.unique' => 'Tag name is already exit.'
    	])->validate();

    	$edit = Tag::find($tag_id);
    	$edit->tag_name = $request->tags_name;

    	if($edit->save()){
    		$request->session()->flash("submit-status",'Tags edited successfully.');
    		return redirect('/tags');
    	}
    }

    public function tags_delete (Request $request,$tag_id) {
    	$delete = Tag::find($tag_id);
    	$delete->status = 5;
    	if($delete->save()){
    		$request->session()->flash("submit-status",'Tag deleted successfully.');
        	return redirect('/tags');
    	}
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Image;
use Validator;
use App\Subject;
USE App\Topic;

class TopicController extends Controller
{
    public function index() {
    	return view('frontend.topic.listing');
    }

    public function topic_add(){
    	$fetch_all_subject = Subject::where('status','1')->get()->toArray();
    	return view('frontend.topic.add')->with('fetch_all_subject',$fetch_all_subject);
    }

    public function topic_add_save (Request $request) {
    	Validator::make($request->all(),[
    		'topic_name' => 'required|unique:topics,topic_name',
    		'subject_type' => 'required',
    		'topic_description' => 'required',
    		// 'topic_file' => 'mimes:jpeg,png,jpg,zip,pdf|max:6144'

    	],[
    		'topic_name.required' => 'Please enter topic name.',
    		'topic_name.unique' => 'Topic name already taken',
    		'subject_type.required' => 'Please select subject type.',
    		'topic_description.required' => 'Pleaes enter topic description.',
    		// 'topic_file.mimes' => 'Please upload correct file.',
    		// 'topic_file.max' => 'Please upload file within 6MB'
    	])->validate();

    	$fileName = '';

		if ($request->hasFile('topic_file')) {
            $file = $request->file('topic_file');

            foreach($file as $key => $value){
            	$fileName1 = array();
            	$file_ext = $value->extension();

	            if($file_ext=='jpeg' || $file_ext=='jpg' || $file_ext=='png'){
	            	$fileName = time().'_'.$value->getClientOriginalName();
	            
		            //thumb destination path
		            $destinationPath_2 = public_path().'/upload/topic_file/resize/';
		            $img = Image::make($value->getRealPath());
		            $img->resize(175, 175, function ($constraint) {
		              $constraint->aspectRatio();
		            })->save($destinationPath_2.'/'.$fileName);
		            //original destination path
		            $destinationPath = public_path().'/upload/topic_file/original/';
		            $value->move($destinationPath,$fileName);
	            }else{
	            	$fileName = time().'_'.$value->getClientOriginalName();
	            	$destinationPath = public_path().'/upload/topic_file/others/';
	            	$value->move($destinationPath,$fileName);
	            }
	            $fileName1[] = $fileName;
	            $fileName = implode(',',$fileName1);
            }

        }

        $add = new Topic();
        $add->topic_name = $request->topic_name;
        $add->subject_id = $request->subject_type;
        $add->topic_description = $request->topic_description;
        $add->upload_file = $fileName;

        if($add->save()){
        	$request->session()->flash("submit-status",'Topic added successfully.');
        	return redirect('/topic');
        }
    }
}

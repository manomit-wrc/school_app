<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Image;
use Validator;
use App\Subject;
use App\Topic;
use App\Tag;

class TopicController extends Controller
{
    public function index() {
    	$fetch_all_topics = Topic::with('subject_details')->where('status','1')->orderby('id','desc')->get()->toArray();
    	return view('frontend.topic.listing')->with('fetch_all_topics',$fetch_all_topics);
    }

    public function topic_add(){
    	$fetch_all_subject = Subject::where('status','1')->get()->toArray();
        $all_tags = Tag::where('status','1')->get()->toArray();
    	return view('frontend.topic.add')->with('fetch_all_subject',$fetch_all_subject)
                                        ->with('all_tags',$all_tags);
    }

    public function topic_add_save (Request $request) {
    	Validator::make($request->all(),[
    		'topic_name' => 'required|unique:topics,topic_name',
    		'subject_type' => 'required',
    		'topic_description' => 'required',
     		'topic_file.*' => 'mimetypes:image/jpeg,image/png,image/jpg,video/mp4,application/zip,application/pdf|max:6144'

    	],[
    		'topic_name.required' => 'Please enter topic name.',
    		'topic_name.unique' => 'Topic name already taken',
    		'subject_type.required' => 'Please select subject type.',
    		'topic_description.required' => 'Pleaes enter topic description.',
    		'topic_file.*.mimetypes' => 'Please upload correct file.',
    		'topic_file.*.max' => 'Please upload file within 6MB'
    	])->validate();

    	

    	$fileName = '';
    	$fileName1 = array();

    	

		if ($request->hasFile('topic_file')) {
            $file = $request->file('topic_file');

            foreach($file as $key => $value){
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
	            $fileName1[$key] = $fileName;
            }
            $fileName = implode(",", $fileName1);
        }

        $add = new Topic();
        $add->topic_name = $request->topic_name;
        $add->subject_id = $request->subject_type;
        $add->topic_description = $request->topic_description;
        $add->upload_file = $fileName;
        $add->status = 1;

        if($add->save()){
            if(count($request->tags) > 0) {
                $add->tags()->attach($request->tags);
            }

        	$request->session()->flash("submit-status",'Topic added successfully.');
        	return redirect('/topic');
        }
    }

    public function topic_delete (Request $request,$topic_id){
    	$delete = Topic::find($topic_id);
    	$delete->status = 5;
    	if($delete->save()){
    		$request->session()->flash("submit-status",'Topic deleted successfully.');
        	return redirect('/topic');
    	}
    }

    public function topic_edit ($topic_id){
        $tags_array = array();
        
    	$fetch_topic_details = Topic::with('subject_details')->where([['id',$topic_id],['status','1']])->get()->toArray();
    	if(empty($fetch_topic_details)){
    		return redirect('/topic');
    	}else{
	    	$all_uploaded_file = explode(",", $fetch_topic_details[0]['upload_file']);

	    	$fetch_all_subject = Subject::where('status','1')->get()->toArray();

            $all_tags = Tag::where('status','1')->get()->toArray();

            $topic_tags = Topic::with('tags')->where('id',$topic_id)->get()->toArray();
            

            foreach ($topic_tags[0]['tags'] as $key => $value) {
              $tags_array[] = $value['id'];
            }

	    	return view('frontend.topic.edit')->with('fetch_topic_details',$fetch_topic_details[0])
    										->with('fetch_all_subject',$fetch_all_subject)
    										->with('all_uploaded_file',$all_uploaded_file)
                                            ->with('all_tags', $all_tags)
                                            ->with('tags_array', $tags_array);
    	}
    }

    public function topic_edit_save (Request $request,$topic_id) {
    	Validator::make($request->all(),[
    		'topic_name' => 'required|unique:topics,topic_name,'.$topic_id,
    		'subject_type' => 'required',
    		'topic_description' => 'required',
    		'topic_file.*' => 'mimetypes:image/jpeg,image/png,image/jpg,video/mp4,application/zip,application/pdf|max:6144'

    	],[
    		'topic_name.required' => 'Please enter topic name.',
    		'topic_name.unique' => 'Topic name already taken',
    		'subject_type.required' => 'Please select subject type.',
    		'topic_description.required' => 'Pleaes enter topic description.',
    		'topic_file.*.mimetypes' => 'Please upload correct file.',
    		'topic_file.*.max' => 'Please upload file within 6MB'
    	])->validate();

    	$fileName = '';
    	$fileName1 = array();

		if ($request->hasFile('topic_file')) {
            $file = $request->file('topic_file');

            foreach($file as $key => $value){
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
	            $fileName1[$key] = $fileName;
            }
            $fileName = implode(",", $fileName1);
        }else{
        	$fileName = $request->existing_file;
        }

        $edit = Topic::find($topic_id);
        $edit->topic_name = $request->topic_name;
        $edit->subject_id = $request->subject_type;
        $edit->topic_description = $request->topic_description;
        $edit->upload_file = $fileName;

        if($edit->save()){
            if(count($request->tags) > 0) {
               $edit->tags()->wherePivot('topic_id', '=', $topic_id)->detach();
               $edit->tags()->attach($request->tags);
            }

        	$request->session()->flash("submit-status",'Topic edited successfully.');
        	return redirect('/topic');
        }
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Filesystem\Filesystem;
use Auth;
use Image;
use Validator;
use App\Course;
use App\Subject;

class SubjectController extends Controller
{
    public function index () {
    	$fetch_all_subject = Subject::with('course_details')->where('status','1')->orderby('id','desc')->get()->toArray();
    	return view('frontend.subject.listing')->with('fetch_all_subject',$fetch_all_subject);
    }

    public function subject_add () {
    	$fetch_all_course = Course::where('status','1')->orderby('id','desc')->get()->toArray();
    	return view('frontend.subject.add')->with('fetch_all_course',$fetch_all_course);
    }

    public function subject_add_save (Request $request) {
    	Validator::make($request->all(),[
    		'sub_full_name' => 'required|unique:subjects,sub_full_name',
    		'sub_short_name' => 'required|unique:subjects,sub_short_name',
    		'course' => 'required',
    		'sub_description' => 'required',
    		'sub_file' => 'mimes:jpeg,png,jpg,zip,pdf|max:6144'
    	],[
    		'sub_full_name.required' => 'Please enter subject full name.',
    		'sub_full_name.unique' => 'Subject name already taken',
    		'sub_short_name.required' => 'Please enter subject short name.',
    		'sub_short_name.unique' => 'Subject short name already taken',
    		'course.required' => 'Please select course type.',
    		'sub_description.required' => 'Please enter subject description.',
    		'sub_file.mimes' => 'Please upload correct file.',
    		'sub_file.max' => 'Please upload file within 6MB'
    	])->validate();

    	$fileName = '';

    	if ($request->hasFile('sub_file')) {
            $file = $request->file('sub_file');

            $file_ext = $file->extension();
            if($file_ext=='jpeg' || $file_ext=='jpg' || $file_ext=='png'){
            	$fileName = time().'_'.$file->getClientOriginalName();
            
	            //thumb destination path
	            $destinationPath_2 = public_path().'/upload/subject_file/resize/';
	            $img = Image::make($file->getRealPath());
	            $img->resize(175, 175, function ($constraint) {
	              $constraint->aspectRatio();
	            })->save($destinationPath_2.'/'.$fileName);
	            //original destination path
	            $destinationPath = public_path().'/upload/subject_file/original/';
	            $file->move($destinationPath,$fileName);
            }else{
            	$fileName = time().'_'.$file->getClientOriginalName();
            	$destinationPath = public_path().'/upload/subject_file/others/';
            	$file->move($destinationPath,$fileName);
            }
        }

        $add = new Subject();
        $add->sub_full_name = $request->sub_full_name;
        $add->sub_short_name = $request->sub_short_name;
        $add->course_id = $request->course;
        $add->sub_desc = $request->sub_description;
        $add->sub_file = $fileName;
        $add->status = 1;

        if($add->save()){
        	$request->session()->flash("submit-status",'Subject added successfully.');
        	return redirect('/subject');
        }
    }

    public function subject_edit($subject_id) {
    	$fetch = Subject::where([['id',$subject_id],['status','1']])->get()->toArray();

    	if(count($fetch) > 0){
    		$subject_details = Subject::with('course_details')->find($subject_id)->toArray();
	    	$path = $subject_details['sub_file'];
			$file_extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

	    	$fetch_all_course = Course::where('status','1')->orderby('id','desc')->get()->toArray();

	    	return view('frontend.subject.edit')->with('subject_details',$subject_details)
	    										->with('fetch_all_course',$fetch_all_course)
	    										->with('file_extension',$file_extension);
    	}else{
    		return redirect('/subject');
    	}
    }

    public function subject_edit_save (Request $request,$subject_id) {

    	Validator::make($request->all(),[
    		'sub_full_name' => 'required|unique:subjects,sub_full_name,'.$subject_id,
    		'sub_short_name' => 'required|unique:subjects,sub_short_name,'.$subject_id,
    		'course' => 'required',
    		'sub_description' => 'required',
    		'sub_file' => 'mimes:jpeg,png,jpg,zip,pdf|max:6144'
    	],[
    		'sub_full_name.required' => 'Please enter subject full name.',
    		'sub_full_name.unique' => 'Subject name already taken',
    		'sub_short_name.required' => 'Please enter subject short name.',
    		'sub_short_name.unique' => 'Subject short name already taken',
    		'course.required' => 'Please select course type.',
    		'sub_description.required' => 'Please enter subject description.',
    		'sub_file.mimes' => 'Please upload correct file.',
    		'sub_file.max' => 'Please upload file within 6MB'
    	])->validate();

    	if ($request->hasFile('sub_file')) {
            $file = $request->file('sub_file');

            $file_ext = $file->extension();
            if($file_ext=='jpeg' || $file_ext=='jpg' || $file_ext=='png'){
            	$fileName = time().'_'.$file->getClientOriginalName();
            
	            //thumb destination path
	            $destinationPath_2 = public_path().'/upload/subject_file/resize/';
	            $img = Image::make($file->getRealPath());
	            $img->resize(175, 175, function ($constraint) {
	              $constraint->aspectRatio();
	            })->save($destinationPath_2.'/'.$fileName);
	            //original destination path
	            $destinationPath = public_path().'/upload/subject_file/original/';
	            $file->move($destinationPath,$fileName);
            }else{
            	$fileName = time().'_'.$file->getClientOriginalName();
            	$destinationPath = public_path().'/upload/subject_file/others/';
            	$file->move($destinationPath,$fileName);
            }
        }else{
        	$fileName = $request->existing_file;
        }

        $edit = Subject::find($subject_id);
        $edit->sub_full_name = $request->sub_full_name;
        $edit->sub_short_name = $request->sub_short_name;
        $edit->course_id = $request->course;
        $edit->sub_desc = $request->sub_description;
        $edit->sub_file = $fileName;

        if($edit->save()){
        	$request->session()->flash("submit-status",'Subject edited successfully.');
        	return redirect('/subject');
        }
    }

    public function subject_delete (Request $request,$subject_id){
    	$delete = Subject::find($subject_id);
    	$delete->status = 5;

    	if($delete->save()){
    		$request->session()->flash("submit-status",'Subject deleted successfully.');
        	return redirect('/subject');
    	}
    }
}

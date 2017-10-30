<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
}

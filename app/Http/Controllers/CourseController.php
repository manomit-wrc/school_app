<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Filesystem\Filesystem;
use App\Category;
use Validator;
use Image;
use App\Course;
use App\Tag;

class CourseController extends Controller
{
    public function index (Request $request) {
    	$course_details = Course::with('category_details')->where('status','1')->get()->toArray();
    	return view('frontend.course.listing')->with('course_details',$course_details);
    }

    public function add () {
    	$category = new \App\Category();
        $categories = $category->tree();
        $category_array = array();
        foreach ($categories->toArray() as  $value) {
           $name = $category->generate_dropdown($value, $value['name']);
           $category_array[] = array('id'=>$value['id'], 'name' => $name);
        }

        $all_tags = Tag::where('status','1')->get()->toArray();
    	return view('frontend.course.add')->with('all_categories',$category_array)
                                        ->with('all_tags',$all_tags);
    }

    public function course_save (Request $request) {
    	Validator::make($request->all(),[
    		'course_full_name' => 'required|unique:courses,full_name',
    		'course_short_name' => 'required|unique:courses,short_name',
    		'course_category' => 'required',
    		'course_description' => 'required',
    		'start_date' => 'required',
    		'end_date' => 'required',
    		'file' => 'mimes:jpeg,png,jpg,mp4,zip,pdf|max:6144'
    	],[
    		'course_full_name.required' => 'Please enter course full name.',
    		'course_full_name.unique' => 'Course name already taken',
    		'course_short_name.required' => 'Please enter course short name.',
    		'course_short_name.unique' => 'Course short name already taken',
    		'course_category.required' => 'Please select course category.',
    		'course_description.required' => 'Please enter course description.',
    		'start_date.required' => 'Please select course start date.',
    		'end_date.required' => 'Please select course end date.',
    		'file.mimes' => 'Please upload correct file.',
    		'file.max' => 'Please upload file within 6MB'
    	])->validate();
        $fileName = '';

    	if ($request->hasFile('file')) {
            $file = $request->file('file');

            $file_ext = $file->extension();
            if($file_ext=='jpeg' || $file_ext=='jpg' || $file_ext=='png'){
            	$fileName = time().'_'.$file->getClientOriginalName();
            
	            //thumb destination path
	            $destinationPath_2 = public_path().'/upload/course_file/resize/';
	            $img = Image::make($file->getRealPath());
	            $img->resize(175, 175, function ($constraint) {
	              $constraint->aspectRatio();
	            })->save($destinationPath_2.'/'.$fileName);
	            //original destination path
	            $destinationPath = public_path().'/upload/course_file/original/';
	            $file->move($destinationPath,$fileName);
            }else{
            	$fileName = time().'_'.$file->getClientOriginalName();
            	$destinationPath = public_path().'/upload/course_file/others/';
            	$file->move($destinationPath,$fileName);
            }
            
            
        }

        $add = new Course();
        $add->full_name = $request->course_full_name;
        $add->short_name = $request->course_short_name;
        $add->category_id = $request->course_category;
        $add->description = $request->course_description;
        $add->description_file = $fileName;
        $add->start_date = $request->start_date;
        $add->end_date = $request->end_date;
        $add->status = 1;

        if($add->save()){
            if(count($request->tags) > 0) {
                $add->tags()->attach($request->tags);
            }
        	$request->session()->flash("submit-status", "Course added successfully.");
            return redirect('/course');
        }
    }

    public function course_edit ($course_id) {
    	$fetch_course = Course::with('category_details')->find($course_id)->toArray();
    	$category = new \App\Category();
        $categories = $category->tree();
        $category_array = array();
        foreach ($categories->toArray() as  $value) {
           $name = $category->generate_dropdown($value, $value['name']);
           $category_array[] = array('id'=>$value['id'], 'name' => $name);
        }
        $tags_array = array();
    	$path = $fetch_course['description_file'];
		$file_extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        $all_tags = Tag::where('status','1')->get()->toArray();

        $course_tags = Course::with('tags')->where('id',$course_id)->get()->toArray();
        

        foreach ($course_tags[0]['tags'] as $key => $value) {
          $tags_array[] = $value['id'];
        }

    	if(count($fetch_course) > 0){
    		return view('frontend.course.edit')->with('fetch_course',$fetch_course)
    											->with('all_categories',$category_array)
    											->with('file_extension',$file_extension)
                                                ->with('all_tags', $all_tags)
                                                ->with('tags_array', $tags_array);
    	}else{
    		return redirect('/course');
    	}
    }

    public function course_edit_submit (Request $request,$course_id) {
    	Validator::make($request->all(),[
    		'course_full_name' => 'required|unique:courses,full_name,'.$course_id,
    		'course_short_name' => 'required|unique:courses,short_name,'.$course_id,
    		'course_category' => 'required',
    		'course_description' => 'required',
    		'start_date' => 'required',
    		'end_date' => 'required',
    		'file' => 'mimes:jpeg,png,jpg,mp4,zip,pdf|max:6144'
    	],[
    		'course_full_name.required' => 'Please enter course full name.',
    		'course_full_name.unique' => 'Course name already taken',
    		'course_short_name.required' => 'Please enter course short name.',
    		'course_short_name.unique' => 'Course short name already taken',
    		'course_category.required' => 'Please select course category.',
    		'course_description.required' => 'Please enter course description.',
    		'start_date.required' => 'Please select course start date.',
    		'end_date.required' => 'Please select course end date.',
    		'file.mimes' => 'Please upload correct file.',
    		'file.max' => 'Please upload file within 6MB'
    	])->validate();

    	if ($request->hasFile('file')) {
            $file = $request->file('file');

            $file_ext = $file->extension();
            if($file_ext=='jpeg' || $file_ext=='jpg' || $file_ext=='png'){
            	$fileName = time().'_'.$file->getClientOriginalName();
            
	            //thumb destination path
	            $destinationPath_2 = public_path().'/upload/course_file/resize/';
	            $img = Image::make($file->getRealPath());
	            $img->resize(175, 175, function ($constraint) {
	              $constraint->aspectRatio();
	            })->save($destinationPath_2.'/'.$fileName);
	            //original destination path
	            $destinationPath = public_path().'/upload/course_file/original/';
	            $file->move($destinationPath,$fileName);
            }else{
            	$fileName = time().'_'.$file->getClientOriginalName();
            	$destinationPath = public_path().'/upload/course_file/others/';
            	$file->move($destinationPath,$fileName);
            }              
        }else{
        	$fileName = $request->existing_file;
        }

        $edit = Course::find($course_id);
        $edit->full_name = $request->course_full_name;
        $edit->short_name = $request->course_short_name;
        $edit->category_id = $request->course_category;
        $edit->description = $request->course_description;
        $edit->description_file = $fileName;
        $edit->start_date = $request->start_date;
        $edit->end_date = $request->end_date;

        if($edit->save()){
            if(count($request->tags) > 0) {
               $edit->tags()->wherePivot('course_id', '=', $course_id)->detach();
               $edit->tags()->attach($request->tags);
            }
            

        	$request->session()->flash("submit-status", "Course edited successfully.");
            return redirect('/course');
        }
    }

    public function course_delete (Request $request,$course_id) {
    	$edit = Course::find($course_id);
    	$edit->status = 5;

    	if($edit->save()){
    		$request->session()->flash("submit-status", "Course deleted successfully.");
            return redirect('/course');
    	}
    }

    public function course_distribution ($course_id) {
        $fetch_course_details = Course::with('subjects.topics')->where('id',$course_id)->get()->toArray();
        $course_start_date = strtotime($fetch_course_details[0]['start_date']);
        $course_end_date = strtotime($fetch_course_details[0]['end_date']);;

        $min_date = min($course_start_date, $course_end_date);
        $max_date = max($course_start_date, $course_end_date);
        $i = 0;

        while (($min_date = strtotime("+1 MONTH", $min_date)) <= $max_date) {
            $i++;
        }
        $months = $i;

        $date1=date_create($fetch_course_details[0]['start_date']);
        $date2=date_create($fetch_course_details[0]['end_date']);
        $diff=date_diff($date1,$date2);
        $total_days = $diff->format("%R%a days");
        $total_weeks = intval($total_days / 7);

        $tempArray = array();
        foreach($fetch_course_details[0]['subjects'] as $key => $value){
            foreach($value['topics'] as $key1 => $value1){
                $tempArray[$key1] = $value1;
            }
        }
        echo "<pre>";
        print_r($tempArray);
        die();
    }
}

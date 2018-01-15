<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Filesystem\Filesystem;
use Auth;
use Image;
use Validator;
use App\Exam;
use App\Subject;
use App\Area;
use App\Tag;
use App\Topic;
use App\TopicAllFile;
use App\TopicContent;
use App\TopicDropboxFile;
use App\TopicEmbedFile;
use App\SubjectExam;
use DB;

class SubjectController extends Controller
{
    public function index() {
    	$fetch_all_subject = Subject::with('subjectIds')->where('status', '1')->orderby('sub_full_name', 'asc')->get()->toArray();
        foreach ($fetch_all_subject as $key => $value) {
            $exam_name = array();
            $no_areas = '';
            if (!empty($value['subject_ids'])) {
                foreach ($value['subject_ids'] as $key1 => $value1) {
                    $exam_id = $value1['exam_id'];
                    $fetch_exam_details = Exam::where('id', $exam_id)->select('name')->get()->toArray();
                    $exam_name[] = $fetch_exam_details[0]['name'];
                }
                $new_exam_name = implode(", ", $exam_name);
                $fetch_all_subject[$key]['exam_names'] = $new_exam_name;
            } else {
                $fetch_all_subject[$key]['exam_names'] = '';
            }

            $fetch_all_area = Area::where('subject_id', $value['id'])->get()->toArray();
            $no_areas = count($fetch_all_area);
            $fetch_all_subject[$key]['no_of_areas'] = $no_areas;
        }
    	return view('frontend.subject.listing')->with('fetch_all_subject', $fetch_all_subject);
    }

    public function subject_add() {
    	$fetch_all_course = Exam::where('status', '1')->orderby('id', 'desc')->get()->toArray();
        $all_tags = Tag::where('status', '1')->get()->toArray();
    	return view('frontend.subject.add')->with('fetch_all_course', $fetch_all_course)->with('all_tags', $all_tags);
    }

    public function subject_add_save(Request $request) {
    	Validator::make($request->all(),[
    		'sub_full_name' => 'required|unique:subjects,sub_full_name',
    		'exam_id' => 'required',
    		'description' => 'required'
    	],[
    		'sub_full_name.required' => 'Please enter subject full name',
            'sub_full_name.unique' => 'Subject full name already taken',
    		'exam_id.required' => 'Please select exam',
    		'description.required' => 'Please enter subject description'
    	])->validate();

        $add = new Subject();
        $add->sub_full_name = trim(ucwords($request->sub_full_name));
        $add->sub_desc = $request->description;
        $add->status = 1;

        if ($add->save()) {
            if (count($request->tags) > 0) {
                $add->tags()->attach($request->tags);
            }
            if (count($request->exam_id) > 0) {
                $add->examIds()->attach($request->exam_id);
            }
        	$request->session()->flash("submit-status",'Subject added successfully.');
        	return redirect('/subject');
        }
    }

    public function subject_edit($subject_id) {
    	$fetch = Subject::where([['id', '=', $subject_id], ['status', '=', '1']])->get()->toArray();
        $tags_array = array();
        $exam_ids_array = array();
    	if (count($fetch) > 0) {
    		$subject_details = Subject::with('exams')->find($subject_id)->toArray();
	    	$fetch_all_course = Exam::where('status', '1')->orderby('id', 'desc')->get()->toArray();
            $all_tags = Tag::where('status', '1')->get()->toArray();
            $subject_tags = Subject::with('tags')->where('id', $subject_id)->get()->toArray();
            if ($subject_tags[0]['tags']) {
                foreach ($subject_tags[0]['tags'] as $key => $value) {
                    $tags_array[] = $value['id'];
                }
            }
            $exam_name = Subject::with('examIds')->where('id', $subject_id)->get()->toArray();
            if (!empty($exam_name[0]['exam_ids'])) {
                foreach ($exam_name[0]['exam_ids'] as $key => $value) {
                    $exam_ids_array[] = $value['id'];
                }
            }
	    	return view('frontend.subject.edit')->with('subject_details',$subject_details)->with('fetch_all_course',$fetch_all_course)->with('all_tags', $all_tags)->with('tags_array', $tags_array)->with('exam_ids_array', $exam_ids_array);
    	} else {
    		return redirect('/subject');
    	}
    }

    public function subject_edit_save(Request $request, $subject_id) {
        $edit = Subject::find($subject_id);
    	Validator::make($request->all(),[
    		'sub_full_name' => 'required|unique:subjects,sub_full_name,'.$subject_id,
    		'exam_id' => 'required',
    		'description' => 'required'
    	],[
    		'sub_full_name.required' => 'Please enter subject full name',
    		'sub_full_name.unique' => 'Subject name already taken',
    		'exam_id.required' => 'Please select exam',
    		'description.required' => 'Please enter subject description'
    	])->validate();

        $edit->sub_full_name = trim(ucwords($request->sub_full_name));
        $edit->sub_desc = $request->description;

        if ($edit->save()) {
            if (count($request->tags) > 0) {
               $edit->tags()->wherePivot('subject_id', '=', $subject_id)->detach();
               $edit->tags()->attach($request->tags);
            }
            if (count($request->exam_id) > 0) {
                $edit->examIds()->wherePivot('subject_id', '=', $subject_id)->detach();
                $edit->examIds()->attach($request->exam_id);
            }
        	$request->session()->flash("submit-status",'Subject updated successfully.');
        	return redirect('/subject');
        }
    }

    public function subject_delete(Request $request, $subject_id) {
        $delete = Subject::find($subject_id);
    	if ($delete->delete()) {
            $delete->tags()->wherePivot('subject_id', '=', $subject_id)->detach();
            $delete->examIds()->wherePivot('subject_id', '=', $subject_id)->detach();
    		$request->session()->flash("submit-status",'Subject deleted successfully.');
        	return redirect('/subject');
    	}
    }
}

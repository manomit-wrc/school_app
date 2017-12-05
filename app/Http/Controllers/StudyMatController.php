<?php

namespace App\Http\Controllers;
use App\Subject;
use App\Area;
use App\Section;
use Validator;
use App\StudyMat;

use Illuminate\Http\Request;

class StudyMatController extends Controller
{
    public function index() {
    	return view ('frontend.studymat.listings');
    }

    public function add_study_mat_view() {
    	$fetch_all_subject = Subject::where('status', '1')->pluck('sub_full_name', 'id')->toArray();
    	return view ('frontend.studymat.add')->with('fetch_all_subject', $fetch_all_subject);
    }

    public function fetch_subject_wise_area(Request $request) {
    	$tempArray = array();
    	$subject_id = $request->subject_id;
    	$fetch_area_details = Area::where([['status', '=', '1'],['subject_id', '=', $subject_id]])->get()->toArray();
    	foreach ($fetch_area_details as $key => $value) {
    		$area_details_array['area_id'] = $value['id'];
    		$area_details_array['area_name'] = $value['name'];
    		$tempArray[] = $area_details_array;
    	}
    	return response()->json(['tempArray' => $tempArray]);
    }

    public function fetch_area_wise_section(Request $request) {
    	$tempArray = array();
    	$area_id = $request->area_id;
    	$fetch_section_details = Section::where([['area_id', $area_id]])->get()->toArray();
    	foreach ($fetch_section_details as $key => $value) {
    		$section_details_array['id'] = $value['id'];
    		$section_details_array['name'] = $value['name'];
    		$tempArray[] = $section_details_array;
    	}
    	return response()->json(['tempArray' => $tempArray]);
    }

    public function study_mat_submit (Request $request) {
    	Validator::make($request->all(),[
    		'subject' => 'required',
    		'area' => 'required',
    		'section' => 'required'
    	],[
    		'subject.required' => 'Please select subject',
    		'area.required' => 'Please select area',
    		'section.required' => 'Please select section'
    	])->validate();

    	$video_arr = array();
    	$pdf_arr = array();
    	$doc_arr = array();
    	$i = 1;
    	if ($request->hasFile('video_files')) {
    		foreach ($request->file('video_files') as $file) {
    			$videoName = time().'_'.$file->getClientOriginalName();
				//original destination path
				$destinationPath = public_path().'/upload/study_video/';
				$file->move($destinationPath, $videoName);
				$video_arr[] = array(
					'video' => $videoName,
					'video_order' => $i
		     	);
				$i++;
    		}
		} else {
			$video_arr = array();
		}
		$i = 1;
		if ($request->hasFile('pdf_files')) {
    		foreach ($request->file('pdf_files') as $file) {
    			$pdfName = time().'_'.$file->getClientOriginalName();
				//original destination path
				$destinationPath = public_path().'/upload/study_pdf/';
				$file->move($destinationPath, $pdfName);
				$pdf_arr[] = array(
					'pdf' => $pdfName,
					'pdf_order' => $i
		     	);
				$i++;
    		}
		} else {
			$pdf_arr = array();
		}
		$i = 1;
		if ($request->hasFile('doc_files')) {
    		foreach ($request->file('doc_files') as $file) {
    			$docName = time().'_'.$file->getClientOriginalName();
				//original destination path
				$destinationPath = public_path().'/upload/study_doc/';
				$file->move($destinationPath, $docName);
				$doc_arr[] = array(
					'doc' => $docName,
					'doc_order' => $i
		     	);
				$i++;
    		}
		} else {
			$doc_arr = array();
		}

    	$add = new StudyMat();
    	$add->subject_id = $request->subject;
    	$add->area_id = $request->area;
    	$add->section_id = $request->section;
		$add->video = serialize($video_arr);
		$add->pdf = serialize($pdf_arr);
		$add->document = serialize($doc_arr);
		$add->description = $request->description;

		if ($add->save()) {
            $request->session()->flash("submit-status", "Study Material added successfully.");
            return redirect('/study_mat');
        } else {
            $request->session()->flash("error-status", "Study Material addition failed.");
            return redirect('/study_mat/add');
        }
    }
}

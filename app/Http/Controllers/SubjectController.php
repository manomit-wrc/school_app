<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Filesystem\Filesystem;
use Auth;
use Image;
use Validator;
use App\Course;
use App\Subject;
use App\Tag;
use App\Topic;
use App\TopicAllFile;
use App\TopicContent;
use App\TopicDropboxFile;
use App\TopicEmbedFile;
use DB;

class SubjectController extends Controller
{
    public function index () {
    	$fetch_all_subject = Subject::with('course_details')->where('status','1')->orderby('id','desc')->get()->toArray();
    	return view('frontend.subject.listing')->with('fetch_all_subject',$fetch_all_subject);
    }

    public function subject_add () {
    	$fetch_all_course = Course::where('status','1')->orderby('id','desc')->get()->toArray();
        $all_tags = Tag::where('status','1')->get()->toArray();
    	return view('frontend.subject.add')->with('fetch_all_course',$fetch_all_course)
                                        ->with('all_tags',$all_tags);
    }

    public function subject_add_save (Request $request) {
    	Validator::make($request->all(),[
    		'sub_full_name' => 'required|unique:subjects,sub_full_name',
    		'sub_short_name' => 'required|unique:subjects,sub_short_name',
    		'course' => 'required',
    		'sub_description' => 'required',
    		'sub_file' => 'mimes:jpeg,png,mp4,jpg,zip,pdf|max:6144'
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
            if(count($request->tags) > 0) {
                $add->tags()->attach($request->tags);
            }
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

            $all_tags = Tag::where('status','1')->get()->toArray();

            $subject_tags = Subject::with('tags')->where('id',$subject_id)->get()->toArray();
            

            foreach ($subject_tags[0]['tags'] as $key => $value) {
              $tags_array[] = $value['id'];
            }

	    	return view('frontend.subject.edit')->with('subject_details',$subject_details)
	    										->with('fetch_all_course',$fetch_all_course)
	    										->with('file_extension',$file_extension)
                                                ->with('all_tags', $all_tags)
                                                ->with('tags_array', $tags_array);
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
    		'sub_file' => 'mimes:jpeg,png,jpg,mp4,zip,pdf|max:6144'
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
            if(count($request->tags) > 0) {
               $edit->tags()->wherePivot('subject_id', '=', $subject_id)->detach();
               $edit->tags()->attach($request->tags);
            }
            
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

    public function topic ($subject_id) {
        $fetch_subject_details = Subject::find($subject_id)->toArray();

        $fetch_all_topic = Topic::with('topic_content')->where([['status','1'],['subject_id',$subject_id]])->orderby('id','desc')->get()->toArray(); 

        return view('frontend.subject.topic_distribution')->with('fetch_subject_details',$fetch_subject_details)
                                                        ->with('fetch_all_topic',$fetch_all_topic);
    }

    public function topic_add (Request $request) {
        $subject_id = $request->subject_id;
        $topic_name = $request->topic_name;

        $add = new Topic();
        $add->topic_name = $topic_name;
        $add->subject_id = $subject_id;
        $add->status = 1;

        if($add->save()){
            // if(count($request->tags) > 0) {
            //     $add->tags()->attach($request->tags);
            // }

            echo 1;
            exit;
        }
    }

    public function topic_upload_post (Request $request,$subject_id,$topic_id,$topic_contect_id) {
        Validator::make($request->all(),[
            'title' => 'required',
        ],[
            'title.required' => "Title can't be left blank. "
        ])->validate();

        if ($request->hasFile('file')) {
            $file = $request->file('file');

            foreach($file as $key => $value){
                $file_ext = $value->extension();

                if($file_ext=='jpeg' || $file_ext=='jpg' || $file_ext=='png'){
                    $fileName = time().'_'.$value->getClientOriginalName();
                
                    //thumb destination path
                    $destinationPath_2 = public_path().'/upload/section_file/resize/';
                    $img = Image::make($value->getRealPath());
                    $img->resize(175, 175, function ($constraint) {
                      $constraint->aspectRatio();
                    })->save($destinationPath_2.'/'.$fileName);
                    //original destination path
                    $destinationPath = public_path().'/upload/section_file/original/';
                    $value->move($destinationPath,$fileName);
                }else{
                    $fileName = time().'_'.$value->getClientOriginalName();
                    $destinationPath = public_path().'/upload/section_file/others/';
                    $value->move($destinationPath,$fileName);
                }
                // $fileName1[$key] = $fileName;
                $add = new TopicAllFile();
                $add->topic_content_id = $topic_contect_id;
                $add->upload_file = $fileName;
                $save = $add->save();
            }

            $edit = TopicContent::find($topic_contect_id);
            $edit->title = $request->title;
            $edit->service_type = $request->optionService;

            if($edit->save()){
                $request->session()->flash("submit-status",'Content added successfully.');
                return redirect('/subject/topic-add/'.$subject_id);
            }

        }else{
            $edit = TopicContent::find($topic_contect_id);
            $edit->title = $request->title;
            $edit->service_type = $request->optionService;

            if($edit->save()){
                $request->session()->flash("submit-status",'Content added successfully.');
                return redirect('/subject/topic-add/'.$subject_id);
            }
        }

    }

    public function topic_file_delete (Request $request, $topic_file_id) {
        $delete = TopicAllFile::find($topic_file_id);

        if($delete->delete()){
            $request->session()->flash("submit-status",'Topic deleted successfully.');
            return redirect('/subject');
        }
    }

    public function upload_file_view (Request $request,$topic_id,$topic_content_id){
        $fetch_section_details = Topic::find($topic_id)->toArray();

        $fetch_topic_content_details = TopicContent::where([['topic_id',$topic_id],['id',$topic_content_id],['status','1']])->get()->toArray();

        return view('frontend.subject.upload_file')->with('fetch_section_details',$fetch_section_details)
                                                ->with('fetch_topic_content_details',$fetch_topic_content_details[0]);
    }

    public function upload_embed_video (Request $request) {
        $add = new TopicEmbedFile();
        $add->topic_content_id = $request->topic_content_id;
        $add->link = $request->file_link;

        if($add->save()){
            echo 1;
            exit();
        }
        
    }

    public function upload_dropbox_file (Request $request) {
        $add = new TopicDropboxFile();
        $add->topic_content_id = $request->topic_content_id;
        $add->link = $request->file_link;

        if($add->save()){
            echo 1;
            exit();
        }
    }

    public function topic_add_content (Request $request){
        $topic_id = $request->topic_id;
        $last_insert_max_id = DB::table('topic_contents')->max('id');
        $new_id = $last_insert_max_id + 1;

        $add = new TopicContent();
        $add->topic_id = $topic_id;
        $add->title = 'VIDEO | PPT | PDF'.' '.$new_id;
        $add->service_type = 1;
        $add->status = 1;

        if($add->save()){
            echo $new_id;
            exit;
        }
    }

    public function topic_content_delete (Request $request,$subject_id,$topic_content_id) {
        $content = TopicContent::find($topic_content_id);

        if($content->delete()){
            $request->session()->flash("submit-status",'Content deleted successfully.');
            return redirect('/subject/topic-add/'.$subject_id);
        }
    }

    public function topic_content_details (Request $request,$topic_id,$topic_content_id) {
        $fetch_content_details = TopicContent::with('content_upload_details','content_dropbox_details','content_embed_details')->where([['topic_id',$topic_id],['id',$topic_content_id]])->get()->toArray();

        $fetch_topic = Topic::find($topic_id)->toArray();

        return view('frontend.subject.content_details')->with('fetch_content_details',$fetch_content_details[0])
                                                        ->with('fetch_topic',$fetch_topic);
    }

    public function content_embedVideo_delete (Request $request,$subject_id,$embed_video_id){
        $video = TopicEmbedFile::find($embed_video_id);

        if($video->delete()){
            $request->session()->flash("submit-status",'File deleted successfully.');
            return redirect('/subject/topic-add/'.$subject_id);
        }
    }

    public function content_dropboxFile_delete (Request $request,$subject_id,$dropbox_file_id) {
        $files = TopicDropboxFile::find($dropbox_file_id);

        if($files->delete()){
            $request->session()->flash("submit-status",'File deleted successfully.');
            return redirect('/subject/topic-add/'.$subject_id);
        }
    }

    public function content_uploadFile_delete (Request $request,$subject_id,$upload_file_id) {
        $upload_file = TopicAllFile::find($upload_file_id);

        if($upload_file->delete()){
            $request->session()->flash("submit-status",'File deleted successfully.');
            return redirect('/subject/topic-add/'.$subject_id);
        }
    }
}

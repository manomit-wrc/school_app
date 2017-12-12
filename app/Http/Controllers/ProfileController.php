<?php
namespace App\Http\Controllers;


header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');

use Illuminate\Http\Request;
use App\Student;
use JWTAuth;
use JWTAuthException;
use Image;
use App\QuestionAnswer;
use App\UserExam;

class ProfileController extends Controller
{
    public function index (Request $request) {
    	$user = JWTAuth::toUser($request->token);
    	if(!empty($user['image'])){
    		$profile_image_link = url('/') .'/upload/app/profile_image/resize/'.$user['image'];
    	}else{
    		$profile_image_link = url('/') .'/upload/app/avatar.png';
    	}
    	return response()->json(['user'=>$user, 'profile_image_link'=>$profile_image_link]);
    }

    public function profile_edit (Request $request) {
    	$user = JWTAuth::toUser($request->token);
    	$user_id = $user['id'];

    	if (!empty($request->profile_image)) {
            $encoded_string = $request->profile_image;
			$imgdata = base64_decode($encoded_string);
			$f = finfo_open();
			$mime_type = finfo_buffer($f, $imgdata, FILEINFO_MIME_TYPE);
			$image_ext = substr($mime_type, 6);

	    	$data = str_replace('data:'.$mime_type.';base64,', '', $encoded_string);
			$data = str_replace(' ', '+', $data);
			$data = base64_decode($data);
			$file = time() . '_profile_image.'.$image_ext;
			$path = url('/') . "/upload/app/profile_image/original/" . $file;

			file_put_contents($path, $data);

			$encoded_string = $request->profile_image;
			$imgdata = base64_decode($encoded_string);

	    	$info    = getimagesizefromstring($imgdata);
	        $old_width = $info[0];
	        $old_height = $info[1];

	        $WIDTH                  = 100; // The size of your new image
			$HEIGHT                 = 100; 

	        //new resource
	        $resource = imagecreatefromstring($imgdata);

	        $resource_copy  = imagecreatetruecolor($WIDTH, $HEIGHT);

	        imagealphablending( $resource_copy , false );
	        imagesavealpha( $resource_copy , true );

	        imagecopyresampled($resource_copy, $resource, 0, 0, 0, 0, $WIDTH, $HEIGHT, $old_width, $old_height);

	        $url = url('/') . "/upload/app/profile_image/resize/".$file;
	        $final = imagepng($resource_copy, $url, 9);

        }else{
        	$file = $request->exit_profile_image;
        }

    	$edit = Student::find($user_id);
    	$edit->first_name = $request->first_name;
    	$edit->last_name = $request->last_name;
    	$edit->mobile_no = $request->mobile_no;
    	$edit->address = $request->address;
    	$edit->city = $request->city;
    	$edit->pincode = $request->pincode;
    	$edit->image = $file;

    	if($edit->save()){
    		return response()->json(['status_code'=>'100','msg'=>'profile edit successfully.']);
    	}else{
    		return response()->json(['status_code'=>'500','msg'=>'profile edit failed.']);
    	}
    }

    public function fetch_question (Request $request) {
    	$subjct_id = $request->subject_id;
    	$exam_id = $request->exam_id;
    	$area_id = $request->area_id;
    	$page_no = $request->page_no;

    	if (isset($page_no)) {
		 $limit = 1;
		    $page = $page_no; //it will telles the current page
		    if ($page && $page > 0) {
		        $start = ($page - 1) * $limit;
		    } else {
		        $start = 0;
		    }
		}

    	$fetch_question_details = QuestionAnswer::where([['subject_id',$subjct_id],['area_id',$area_id],['status','1']])->offset($start)->limit($limit)->get()->toArray();

    	if(count($fetch_question_details) > 0 ){
    		$question_type = $fetch_question_details[0]['question_type'];
	    	if($question_type == 'image'){
	    		$question = url('/') . "/upload/question_file/resize/".$fetch_question_details[0]['question'];
	    	}
	    	if($question_type == 'text'){
	    		$question = $fetch_question_details[0]['question'];
	    	}

	    	$option = unserialize($fetch_question_details[0]['answer']);
	    	$option_image_link = url('/') . "/upload/answers_file/resize/";

	    	
	    	$correct_answer = count(unserialize($fetch_question_details[0]['correct_answer']));
	    	if($correct_answer > 1){
	    		$answer_type = 'multiple';
	    	}else{
	    		$answer_type = 'single';
	    	}

	    	return response()->json(['status_code'=>'100','question'=>$question,'option'=>$option,'option_image_link'=>$option_image_link,'answer_type'=>$answer_type]);
    	}else{
    		return response()->json(['status_code'=>'404','msg'=>'No questions found.']);
    	}
    }

    public function fetch_user_ans (Request $request) {
    	$area_id = $request->area_id;

    	$fetch_user_ans_details = UserExam::where([['area_id',$area_id]])->get()->toArray();
    	if(count($fetch_user_ans_details) > 0){
    		$i=0;

	    	foreach($fetch_user_ans_details as $key => $value){
	    		$user_ans = unserialize($value['user_answer']);

	    		$fetch_correct_ans_details = QuestionAnswer::where([['area_id',$area_id],['id',$value['question_id']]])->select('correct_answer')->get()->toArray();
	    		$correct_answer = unserialize($fetch_correct_ans_details[0]['correct_answer']);

	    		if($correct_answer == $user_ans){
	    			$i++;
	    		}
	    	}

	    	return response()->json(['status_code'=>'100','i'=>$i]);
    	}else{
    		return response()->json(['status_code'=>'404','msg'=>'No answer found.']);
    	}
    }
}

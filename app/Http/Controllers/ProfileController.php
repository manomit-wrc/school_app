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
use Validator;
use Config;
use Image;
use App\QuestionAnswer;
use App\UserExam;
use Illuminate\Support\Facades\Mail;
use App\Mail\ForgotPassword;

class ProfileController extends Controller
{
    public function index (Request $request) {
        $token = $request->header('token')?$request->header('token'):$request->token;
    	$user = JWTAuth::toUser($token);
    	if(!empty($user['image'])){
    		$profile_image_link = url('/') .'/upload/app/profile_image/resize/'.$user['image'];
    	}else{
    		$profile_image_link = url('/') .'/upload/app/profile_image/avatar.png';
    	}
    	return response()->json(['user'=>$user,'profile_image_link'=>$profile_image_link,'status_code'=>200,'msg'=>'success']);
    }

    public function profile_edit (Request $request) {
    	$user = JWTAuth::toUser($request->token);
    	$user_id = $user['id'];

   //  	if (!empty($request->profile_image)) {
   //          $encoded_string = $request->profile_image;
			// $imgdata = base64_decode($encoded_string);
			// $f = finfo_open();
			// $mime_type = finfo_buffer($f, $imgdata, FILEINFO_MIME_TYPE);
			// $image_ext = substr($mime_type, 6);

	  //   	$data = str_replace('data:'.$mime_type.';base64,', '', $encoded_string);
			// $data = str_replace(' ', '+', $data);
			// $data = base64_decode($data);
			// $file = time() . '_profile_image.'.$image_ext;
			// $path = url('/') . "/upload/app/profile_image/original/" . $file;

			// file_put_contents($path, $data);

			// $encoded_string = $request->profile_image;
			// $imgdata = base64_decode($encoded_string);

	  //   	$info    = getimagesizefromstring($imgdata);
	  //       $old_width = $info[0];
	  //       $old_height = $info[1];

	  //       $WIDTH                  = 100; // The size of your new image
			// $HEIGHT                 = 100; 

	  //       //new resource
	  //       $resource = imagecreatefromstring($imgdata);

	  //       $resource_copy  = imagecreatetruecolor($WIDTH, $HEIGHT);

	  //       imagealphablending( $resource_copy , false );
	  //       imagesavealpha( $resource_copy , true );

	  //       imagecopyresampled($resource_copy, $resource, 0, 0, 0, 0, $WIDTH, $HEIGHT, $old_width, $old_height);

	  //       $url = url('/') . "/upload/app/profile_image/resize/".$file;
	  //       $final = imagepng($resource_copy, $url, 9);

   //      }else{
   //      	$file = $request->exit_profile_image;
   //      }

    	$edit = Student::find($user_id);
    	// $edit->first_name = $request->first_name;
    	// $edit->last_name = $request->last_name;
    	$edit->mobile_no = $request->mobile_no;
    	// $edit->address = $request->address;
    	// $edit->city = $request->city;
    	// $edit->pincode = $request->pincode;
    	// $edit->image = $file;

    	if($edit->save()){
    		return response()->json(['status_code'=>'200','msg'=>'profile edit successfully.']);
    	}else{
    		return response()->json(['status_code'=>'500','msg'=>'profile edit failed.']);
    	}

    }

    public function fetch_question (Request $request) {
    	$subjct_id = $request->subject_id;
    	$exam_id = $request->exam_id;
    	$area_id = $request->area_id;
        $section_id = $request->section_id;
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

    	$fetch_question_details = QuestionAnswer::where([['subject_id',$subjct_id],['area_id',$area_id],['status','1'],['section_id',$section_id],['exam_id','like','%'.$exam_id.'%']])->offset($start)->limit($limit)->get()->toArray();

    	if(count($fetch_question_details) > 0 ){
            if($fetch_question_details[0]['option_type'] == 'mcq'){
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

                if(!empty($fetch_question_details[0]['explanation_file'])){
                    $explanation_file_link = url('/') . "/upload/explanation_file/original/".$fetch_question_details[0]['explanation_file'];
                }else{
                    $explanation_file_link = "N/A";
                }

                return response()->json(['status_code'=>'200','question'=>$question,'option_type'=>'mcq','option'=>$option,'option_image_link'=>$option_image_link,'answer_type'=>$answer_type,'explanation_details'=>$fetch_question_details[0]['explanation_details'],'explanation_file_link'=>$explanation_file_link]);
            }

            if($fetch_question_details[0]['option_type'] == 'numeric'){
                $question_type = $fetch_question_details[0]['question_type'];
                if($question_type == 'image'){
                    $question = url('/') . "/upload/question_file/resize/".$fetch_question_details[0]['question'];
                }
                if($question_type == 'text'){
                    $question = $fetch_question_details[0]['question'];
                }

                if(!empty($fetch_question_details[0]['explanation_file'])){
                    $explanation_file_link = url('/') . "/upload/explanation_file/original/".$fetch_question_details[0]['explanation_file'];
                }else{
                    $explanation_file_link = "N/A";
                }

                return response()->json(['status_code'=>'200','question'=>$question,'option_type'=>'numeric','explanation_file_link'=>$explanation_file_link,'explanation_details'=>$fetch_question_details[0]['explanation_details']]);
            }	    	
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

	    	$total_correct_answer = $i;
	    	$total_no_of_question = count(QuestionAnswer::where('area_id',$area_id)->get()->toArray());

	    	$marks = ($total_correct_answer / $total_no_of_question) * 100 .'%' ;

	    	return response()->json(['status_code'=>'200','marks'=>$marks]);
    	}else{
    		return response()->json(['status_code'=>'404','msg'=>'No answer found.']);
    	}
    }

    public function forgot_password (Request $request) {
    	$user_email = trim($request->email);

    	// $user = JWTAuth::toUser($request->token);
    	$user = Student::where('email',$user_email)->get()->toArray();

    	if(count($user) > 0){
    		$otp= rand(1000,5000);
    		$user_name = ucwords($user[0]['username']);
    		try{
    			Mail::to($user_email)->send(new ForgotPassword($otp,$user_name));
                //edit student table
    			$student = Student::find($user[0]['id']);
    			$student->otp = $otp;
    			if ($student->save()) {
		            return response()->json(['msg' => 'Email send successfully with OTP.', 'status_code' =>'200']);
		        }
    		}catch(\Exception $e){

			    return response()->json(['code'=>500,'msg'=>'error']);
			}
    	}else{
    		return response()->json(['status_code'=>'404', 'msg'=>'Email is wrong. Please give correct email.']);
    	}
    }

    public function otp_verification(Request $request){
    	// $user_email = $request->email;
    	$otp = $request->otp;

    	$fetch_user_deatils = Student::where('otp',$otp)->first();

        $payload = JWTAuth::fromUser($fetch_user_deatils, ['username' => $fetch_user_deatils->username]);
        $token = $payload;

    	if(count($fetch_user_deatils) > 0){
    		$id = $fetch_user_deatils->id;

    		$edit = Student::find($id);
    		$edit->status = 1;

    		if($edit->save()){
                return response()->json(['status_code'=>200, 'msg'=>'Your have successfully acivate your account.','token' => $token]);

    		}

    	}else{
    		return response()->json(['status_code'=>404, 'msg'=>'Invalid email or OTP.']);
    	}
    }

    public function forgot_pw_verification (Request $request) {
        $otp = $request->otp;
        $pw = $request->password;

        $fetch_user_details = Student::where('otp',$otp)->get()->toArray();
        if(count($fetch_user_details) > 0){
            $id = $fetch_user_details[0]['id'];

            $edit = Student::find($id);
            $edit->password = bcrypt($pw);

            if($edit->save()){
                return response()->json(['status_code'=>200, 'msg'=>'Your have successfully changed your password.']);
            }

        }else{
            return response()->json(['status_code'=>404, 'msg'=>'Invalid OTP.']);
        }
    }
}

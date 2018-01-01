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
use App\Banner;
use App\UserMarks;

class ProfileController extends Controller
{
    public function index(Request $request) {
        $token = $request->header('token') ? $request->header('token') : $request->token;
    	$user = JWTAuth::toUser($token);
    	if (!empty($user['image'])) {
    		$profile_image_link = url('/') . '/upload/app/profile_image/resize/' . $user['image'];
    	} else {
    		$profile_image_link = url('/') . '/upload/app/profile_image/avatar.png';
    	}
    	return response()->json(['user' => $user, 'profile_image_link' => $profile_image_link, 'status_code' => 200, 'msg' => 'success']);
    }

    public function profile_edit(Request $request) {
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
        $edit->first_name = ($request->first_name != '') ? $request->first_name : $edit->first_name;
    	$edit->last_name = ($request->last_name != '') ? $request->last_name : $edit->last_name;
    	$edit->mobile_no = ($request->mobile_no != '') ? $request->mobile_no : $edit->mobile_no;
    	$edit->address = ($request->address != '') ? $request->address : $edit->address;
    	$edit->city = ($request->city != '') ? $request->city : $edit->city;
        $edit->country = ($request->country != '') ? $request->country : $edit->country;
    	$edit->pincode = ($request->pincode != '') ? $request->pincode : $edit->pincode;
    	//$edit->image = $file;

    	if ($edit->save()) {
    		return response()->json(['status_code' => 200, 'msg' => 'profile updated successfully.']);
    	} else {
    		return response()->json(['status_code' => 500, 'msg' => 'profile updation failed.']);
    	}
    }

    public function fetch_question(Request $request) {
    	$subject_id = $request->subject_id;
    	$exam_id = $request->exam_id;
    	$area_id = $request->area_id;
        $section_id = $request->section_id;
        $level = $request->level;
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

    	$fetch_question_details = QuestionAnswer::where([['subject_id', $subject_id], ['area_id', $area_id], ['status', '1'], ['section_id', $section_id], ['exam_id', 'like', '%'.$exam_id.'%'], ['level', '=', $level]])->offset($start)->limit($limit)->get()->toArray();

    	if (count($fetch_question_details) > 0) {
            if ($fetch_question_details[0]['option_type'] == 'mcq') {
                $question_type = $fetch_question_details[0]['question_type'];
                if ($question_type == 'image') {
                    $question_url = url('/') . "/upload/question_file/resize/" . $fetch_question_details[0]['question'];
                    $question = "<img src='".$question_url."' alt='' />";
                }
                if ($question_type == 'text') {
                    $question = $fetch_question_details[0]['question'];
                }

                $option = unserialize($fetch_question_details[0]['answer']);
                $option['optionA_val'] = '1';
                $option['optionB_val'] = '2';
                $option['optionC_val'] = '3';
                $option['optionD_val'] = '4';
                $option['optionE_val'] = '5';

                $option_image_link = url('/') . "/upload/answers_file/resize/";
                
                $correct_answer = count(unserialize($fetch_question_details[0]['correct_answer']));
                if ($correct_answer > 1) {
                    $answer_type = 'multiple';
                } else {
                    $answer_type = 'single';
                }

                if (!empty($fetch_question_details[0]['explanation_file'])) {
                    $explanation_file_link = url('/') . "/upload/explanation_file/resize/" . $fetch_question_details[0]['explanation_file'];
                } else {
                    $explanation_file_link = "N/A";
                }

                return response()->json(['status_code' => 200, 'question_id' => $fetch_question_details[0]['id'], 'question' => $question, 'question_type' => $fetch_question_details[0]['question_type'], 'option_type' => 'mcq', 'option' => $option, 'option_image_link' => $option_image_link, 'answer_type' => $answer_type, 'explanation_details' => $fetch_question_details[0]['explanation_details'], 'explanation_file_link' => $explanation_file_link]);
            }

            if ($fetch_question_details[0]['option_type'] == 'numeric') {
                $question_type = $fetch_question_details[0]['question_type'];
                if ($question_type == 'image') {
                    $question = url('/') . "/upload/question_file/resize/" . $fetch_question_details[0]['question'];
                }
                if ($question_type == 'text') {
                    $question = $fetch_question_details[0]['question'];
                }

                if (!empty($fetch_question_details[0]['explanation_file'])) {
                    $explanation_file_link = url('/') . "/upload/explanation_file/resize/" . $fetch_question_details[0]['explanation_file'];
                } else {
                    $explanation_file_link = "N/A";
                }

                return response()->json(['status_code' => 200, 'question_id' => $fetch_question_details[0]['id'], 'question' => $question, 'question_type' => $fetch_question_details[0]['question_type'], 'option_type' => 'numeric', 'explanation_file_link' => $explanation_file_link, 'explanation_details' => $fetch_question_details[0]['explanation_details']]);
            }
    	} else {
    		return response()->json(['status_code' => 404, 'msg' => 'No questions found.']);
    	}
    }

    public function fetch_user_ans(Request $request) {
        $user = JWTAuth::toUser($request->token);
        $user_id = $user['id'];

        $exam_id = $request->exam_id;
        $subject_id = $request->subject_id;
    	$area_id = $request->area_id;
        $section_id = $request->section_id;
        $level = $request->level;

        $fetch_question_list = QuestionAnswer::where([['exam_id', 'like', '%'.$exam_id.'%'], ['subject_id', '=', $subject_id], ['area_id', '=', $area_id], ['section_id', '=', $section_id], ['level', '=', $level]])->get()->toArray();
        $total_no_of_question = count($fetch_question_list);

        $i = 0;
        foreach ($fetch_question_list as $key => $value) {
            $fetch_user_ans_details = UserExam::where([['student_id', '=', $user_id], ['exam_id', '=', $exam_id], ['subject_id', '=', $subject_id], ['area_id', '=', $area_id], ['section_id', '=', $section_id], ['question_id', '=', $value['id']]])->orderBy('id', 'desc')->take(1)->get()->toArray();
            if ($value['option_type'] == 'mcq') {
                $user_answer = unserialize($fetch_user_ans_details[0]['user_answer']);
                $correct_answer = unserialize($value['correct_answer']);
                if ($correct_answer == $user_answer) {
                    $i++;
                }
            } else if ($value['option_type'] == 'numeric') {
                $numeric_answer = trim($fetch_user_ans_details[0]['numeric_ans']);
                $correct_answer = trim($value['numeric_answer']);
                if ($correct_answer == $numeric_answer) {
                    $i++;
                }
            }
            $total_correct_answer = $i;
        }

        $marks = ($total_correct_answer / $total_no_of_question) * 100 . '%';

        if (!empty($marks)) {
            $add = new UserMarks();
            $add->student_id = $user_id;
            $add->exam_id = $exam_id;
            $add->area_id = $area_id;
            $add->subject_id = $subject_id;
            $add->section_id = $section_id;
            $add->percentile = $marks;
            $add->total_correct_ans = $total_correct_answer;
            $add->save();
        }

        return response()->json(['status_code' => 200, 'total_no_of_question' => $total_no_of_question, 'total_correct_answer' => $total_correct_answer, 'marks' => $marks]);
    }

    public function forgot_password(Request $request) {
    	$user_email = trim($request->email);
    	// $user = JWTAuth::toUser($request->token);
    	$user = Student::where('email', $user_email)->get()->toArray();

    	if (count($user) > 0) {
    		$otp = rand(1000, 5000);
    		$user_name = ucwords($user[0]['username']);
    		try {
    			Mail::to($user_email)->send(new ForgotPassword($otp, $user_name));
                //edit student table
    			$student = Student::find($user[0]['id']);
    			$student->otp = $otp;
    			if ($student->save()) {
		            return response()->json(['msg' => 'Email send successfully with OTP.', 'status_code' => 200]);
		        }
    		} catch(\Exception $e) {
			    return response()->json(['status_code' => 500, 'msg' => 'error']);
			}
    	} else {
    		return response()->json(['status_code' => 404, 'msg' => 'Email is wrong. Please give correct email.']);
    	}
    }

    public function otp_verification(Request $request) {
    	$otp = $request->otp;
    	$fetch_user_deatils = Student::where('otp', $otp)->first();
        $payload = JWTAuth::fromUser($fetch_user_deatils, ['username' => $fetch_user_deatils->username]);
        $token = $payload;
    	if (count($fetch_user_deatils) > 0) {
    		$id = $fetch_user_deatils->id;
    		$edit = Student::find($id);
    		$edit->status = 1;
            $user = JWTAuth::toUser($token);
            $exam_id = $user['exam_id'];
            if ($user['image'] == '') {
                $image = url('/') .'/upload/profile_image/default.png';
            } else {
                $image = url('/') .'/upload/profile_image/resize/'. $user['image'];
            }
    		if ($edit->save()) {
                return response()->json([
                    'status_code' => 200,
                    'msg' => 'Your have successfully acivate your account.',
                    'token' => $token,
                    'first_name' => ucfirst($user['first_name']),
                    'last_name' => ucfirst($user['last_name']),
                    'image' => $image,
                    'exam_id' => $exam_id
                ]);
    		}
    	} else {
    		return response()->json(['status_code' => 404, 'msg' => 'Invalid email or OTP.']);
    	}
    }

    public function forgot_pw_verification(Request $request) {
        $otp = $request->otp;
        $pw = $request->password;

        $fetch_user_details = Student::where('otp',$otp)->get()->toArray();
        if (count($fetch_user_details) > 0) {
            $id = $fetch_user_details[0]['id'];
            $edit = Student::find($id);
            $edit->password = bcrypt($pw);
            if ($edit->save()) {
                return response()->json(['status_code' => 200, 'msg' => 'Your have successfully changed your password.']);
            }
        } else {
            return response()->json(['status_code' => 404, 'msg' => 'Invalid OTP.']);
        }
    }

    public function banner(Request $request) {
        $all_banner = Banner::all();
        $banner_image_link = url('/') . '/upload/banner_file/resize/';
        return response()->json(['status_code' => 200, 'msg' => 'Success', 'all_banner' => $all_banner, 'banner_image_link' => $banner_image_link]);
    }

    public function review_exam(Request $request) {
        $user = JWTAuth::toUser($request->token);
        $user_id = $user['id'];

        $subject_id = $request->subject_id;
        $exam_id = $request->exam_id;
        $area_id = $request->area_id;
        $section_id = $request->section_id;
        $level = $request->level;
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

        $fetch_question_details = QuestionAnswer::where([['subject_id', '=', $subject_id], ['area_id', '=', $area_id], ['status', '=', '1'], ['section_id', '=', $section_id], ['exam_id', 'like', '%'.$exam_id.'%'], ['level', '=', $level]])->offset($start)->limit($limit)->get()->toArray();

        if (count($fetch_question_details) > 0) {
            if ($fetch_question_details[0]['option_type'] == 'mcq') {
                $question_type = $fetch_question_details[0]['question_type'];
                if ($question_type == 'image') {
                    $question_url = url('/') . "/upload/question_file/resize/" . $fetch_question_details[0]['question'];
                    $question = "<img src='".$question_url."' alt='' />";
                }
                if ($question_type == 'text') {
                    $question = $fetch_question_details[0]['question'];
                }

                $option = unserialize($fetch_question_details[0]['answer']);
                $option['optionA_val'] = '1';
                $option['optionB_val'] = '2';
                $option['optionC_val'] = '3';
                $option['optionD_val'] = '4';
                $option['optionE_val'] = '5';

                $option_image_link = url('/') . "/upload/answers_file/resize/";
                
                $count_correct_answer = count(unserialize($fetch_question_details[0]['correct_answer']));
                if ($count_correct_answer > 1) {
                    $answer_type = 'multiple';
                } else {
                    $answer_type = 'single';
                }

                if (!empty($fetch_question_details[0]['explanation_file'])) {
                    $explanation_file_link = url('/') . "/upload/explanation_file/resize/" . $fetch_question_details[0]['explanation_file'];
                } else {
                    $explanation_file_link = "N/A";
                }

                $fetch_user_ans_details = UserExam::where([['student_id', '=', $user_id], ['exam_id', '=', $exam_id], ['subject_id', '=', $subject_id], ['area_id', '=', $area_id], ['section_id', '=', $section_id], ['question_id', '=', $fetch_question_details[0]['id']]])->orderBy('id', 'desc')->take(1)->get()->toArray();
                $user_answer = unserialize($fetch_user_ans_details[0]['user_answer']);
                $user_answer = $user_answer[0];
                $correct_answer = unserialize($fetch_question_details[0]['correct_answer']);
                $correct_answer = $correct_answer[0];

                return response()->json(['status_code' => 200, 'question' => $question, 'question_type' => $fetch_question_details[0]['question_type'], 'option_type' => 'mcq', 'option' => $option, 'option_image_link' => $option_image_link, 'answer_type' => $answer_type, 'user_answer' => $user_answer, 'correct_answer' => $correct_answer, 'explanation_details' => $fetch_question_details[0]['explanation_details'], 'explanation_file_link' => $explanation_file_link]);
            }

            if ($fetch_question_details[0]['option_type'] == 'numeric') {
                $question_type = $fetch_question_details[0]['question_type'];
                if ($question_type == 'image') {
                    $question = url('/') . "/upload/question_file/resize/" . $fetch_question_details[0]['question'];
                }
                if ($question_type == 'text') {
                    $question = $fetch_question_details[0]['question'];
                }

                if (!empty($fetch_question_details[0]['explanation_file'])) {
                    $explanation_file_link = url('/') . "/upload/explanation_file/resize/" . $fetch_question_details[0]['explanation_file'];
                } else {
                    $explanation_file_link = "N/A";
                }

                $fetch_user_ans_details = UserExam::where([['student_id', '=', $user_id], ['exam_id', '=', $exam_id], ['subject_id', '=', $subject_id], ['area_id', '=', $area_id], ['section_id', '=', $section_id], ['question_id', '=', $fetch_question_details[0]['id']]])->orderBy('id', 'desc')->take(1)->get()->toArray();
                $user_answer = $fetch_user_ans_details[0]['numeric_ans'];
                $correct_answer = $fetch_question_details[0]['numeric_answer'];

                return response()->json(['status_code' => 200, 'question' => $question, 'question_type' => $fetch_question_details[0]['question_type'], 'option_type' => 'numeric', 'user_answer' => $user_answer, 'correct_answer' => $correct_answer, 'explanation_file_link' => $explanation_file_link, 'explanation_details' => $fetch_question_details[0]['explanation_details']]);
            }
        } else {
            return response()->json(['status_code' => 404, 'msg' => 'No questions found.']);
        }
    }
}

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
use App\ExamQuestionAnswer;
use App\UserExam;
use App\UserExamAnswer;
use Illuminate\Support\Facades\Mail;
use App\Mail\ForgotPassword;
use App\Banner;
use App\UserMarks;
use App\UserExamMarks;
use App\StudentRatingLog;
use App\ExamTimer;
use App\SubjectExam;

class ProfileController extends Controller
{
    public function index(Request $request) {
        $token = $request->header('token') ? $request->header('token') : $request->token;
    	$user = JWTAuth::toUser($token);
    	if (!empty($user['image'])) {
    		$profile_image_link = url('/') . '/upload/profile_image/original/' . $user['image'];
    	} else {
    		$profile_image_link = url('/') . '/upload/profile_image/default.png';
    	}
    	return response()->json(['user' => $user, 'profile_image_link' => $profile_image_link, 'status_code' => 200, 'msg' => 'success']);
    }

    public function profile_edit(Request $request) {
    	$user = JWTAuth::toUser($request->token);
    	$user_id = $user['id'];

        if (!empty($request->profile_image)) {
            $image_str = str_replace(' ', '+', $request->profile_image);
            $png_url = "profile-".time().".png";
            $path = public_path().'/upload/profile_image/original/' . $png_url;
            Image::make(file_get_contents("data:image/png;base64,".$image_str))->save($path);     
            $file = $png_url;
        } else {
            $file = '';
        }

    	$edit = Student::find($user_id);
        $edit->first_name = ($request->first_name != '') ? $request->first_name : $edit->first_name;
    	$edit->last_name = ($request->last_name != '') ? $request->last_name : $edit->last_name;
    	$edit->mobile_no = ($request->mobile_no != '') ? $request->mobile_no : $edit->mobile_no;
    	$edit->address = ($request->address != '') ? $request->address : $edit->address;
    	$edit->city = ($request->city != '') ? $request->city : $edit->city;
        $edit->country = ($request->country != '') ? $request->country : $edit->country;
    	$edit->pincode = ($request->pincode != '') ? $request->pincode : $edit->pincode;
        if ($file != '') {
    	   $edit->image = $file;
        }

    	if ($edit->save()) {
            $student_details = $edit->toArray();
            if ($student_details['image'] == '') {
                $image = url('/') .'/upload/profile_image/default.png';
            } else {
                $image = url('/') .'/upload/profile_image/original/'. $student_details['image'];
            }
            $first_name = ucfirst($student_details['first_name']);
            $last_name = ucfirst($student_details['last_name']);
            $rating = $student_details['current_rating'];
    		return response()->json(['status_code' => 200, 'msg' => 'profile updated successfully.', 'first_name' => $first_name, 'last_name' => $last_name, 'image' => $image, 'rating' => $rating]);
    	} else {
    		return response()->json(['status_code' => 500, 'msg' => 'profile updation failed.']);
    	}
    }

    public function fetch_question(Request $request) {
        $user = JWTAuth::toUser($request->token);
        $user_id = $user['id'];

        $exam_id = $request->exam_id;
        $subject_id = $request->subject_id;
        $area_id = $request->area_id;
        $section_id = $request->section_id;
        $level = $request->level;

        $question_list = array();

        if ($level == 1) {
            $access = 1;
        } else if ($level == 4 || $level == 5) {
            if ($user['subscription'] == 0) {
                $access = 2;
            } else {
                $pre_level = $level - 1;
                $fetch_user_marks = UserMarks::where([['student_id', '=', $user_id], ['exam_id', '=', $exam_id], ['subject_id', '=', $subject_id], ['area_id', '=', $area_id], ['section_id', '=', $section_id], ['level', '=', $pre_level]])->orderBy('id', 'desc')->take(1)->get()->toArray();
                if (count($fetch_user_marks) > 0) {
                    $fetch_correct_ans = $fetch_user_marks[0]['total_correct_ans'];
                    if ($fetch_correct_ans >= 3) {
                        $access = 1;
                    } else {
                        $access = 0;
                    }
                } else {
                    $access = 0;
                }
            }
        } else {
            $pre_level = $level - 1;
            $fetch_user_marks = UserMarks::where([['student_id', '=', $user_id], ['exam_id', '=', $exam_id], ['subject_id', '=', $subject_id], ['area_id', '=', $area_id], ['section_id', '=', $section_id], ['level', '=', $pre_level]])->orderBy('id', 'desc')->take(1)->get()->toArray();
            if (count($fetch_user_marks) > 0) {
                $fetch_correct_ans = $fetch_user_marks[0]['total_correct_ans'];
                if ($fetch_correct_ans >= 3) {
                    $access = 1;
                } else {
                    $access = 0;
                }
            } else {
                $access = 0;
            }
        }

        if ($access == 1) {
            $fetch_question_details = QuestionAnswer::where([['subject_id', '=', $subject_id], ['area_id', '=', $area_id], ['status', '=', '1'], ['section_id', '=', $section_id], ['exam_id', 'like', '%'.$exam_id.'%'], ['level', '=', $level]])->orderByRaw("RAND()")->take(5)->get()->toArray();

            if (count($fetch_question_details) > 0) {
                foreach ($fetch_question_details as $key => $value) {
                    if ($value['option_type'] == 'mcq') {
                        $question_type = $value['question_type'];
                        if ($question_type == 'image') {
                            $question_url = url('/') . "/upload/question_file/resize/" . $value['question'];
                            $question = "<img src='".$question_url."' alt='' />";
                        }
                        if ($question_type == 'text') {
                            $question = $value['question'];
                        }

                        $option = unserialize($value['answer']);
                        $option['optionA_val'] = '1';
                        $option['optionB_val'] = '2';
                        $option['optionC_val'] = '3';
                        $option['optionD_val'] = '4';
                        $option['optionE_val'] = '5';

                        $option_image_link = url('/') . "/upload/answers_file/resize/";
                        
                        $correct_answer = count(unserialize($value['correct_answer']));
                        if ($correct_answer > 1) {
                            $answer_type = 'multiple';
                        } else {
                            $answer_type = 'single';
                        }

                        if (!empty($value['explanation_file'])) {
                            $explanation_file_link = url('/') . "/upload/explanation_file/resize/" . $value['explanation_file'];
                        } else {
                            $explanation_file_link = "N/A";
                        }
                    }
                    else if ($value['option_type'] == 'numeric') {
                        $question_type = $value['question_type'];
                        if ($question_type == 'image') {
                            $question = url('/') . "/upload/question_file/resize/" . $value['question'];
                        }
                        if ($question_type == 'text') {
                            $question = $value['question'];
                        }

                        if (!empty($value['explanation_file'])) {
                            $explanation_file_link = url('/') . "/upload/explanation_file/resize/" . $value['explanation_file'];
                        } else {
                            $explanation_file_link = "N/A";
                        }

                        $option = "";
                        $option_image_link = "";
                        $answer_type = '';
                    }

                    $question_list[] = array(
                        'question_id' => $value['id'],
                        'question_type' => $value['question_type'],
                        'question' => $question,
                        'option_type' => $value['option_type'],
                        'option' => $option,
                        'option_image_link' => $option_image_link,
                        'answer_type' => $answer_type,
                        'explanation_details' => $value['explanation_details'],
                        'explanation_file_link' => $explanation_file_link
                    );
                }

                $fetch_timer = ExamTimer::all()->toArray();
                $section_test_timer = $fetch_timer[0]['section_test'];
                return response()->json(['status_code' => 200, 'msg' => 'Success', 'data' => $question_list, 'time' => $section_test_timer]);
            } else {
                return response()->json(['status_code' => 404, 'msg' => 'No question found.']);
            }
        } else if ($access == 2) {
            return response()->json(['status_code' => 500, 'msg' => 'Your subscription is free. Please pay to get access to all exams!']);
        } else {
            return response()->json(['status_code' => 500, 'msg' => 'You are not eligible to take this test!']);
        }
    }

    public function fetch_area_question(Request $request) {
        $user = JWTAuth::toUser($request->token);
        $user_id = $user['id'];

        $exam_id = ($request->exam_id != '') ? $request->exam_id : 0;
        $subject_id = ($request->subject_id != '') ? $request->subject_id : 0;
        $area_id = ($request->area_id != '') ? $request->area_id : 0;
        $section_id = 0;
        $level = ($request->level != '') ? $request->level : 0;

        $question_list = array();

        $fetch_question_details = QuestionAnswer::where([['subject_id', '=', $subject_id], ['area_id', '=', $area_id], ['status', '=', '1'], ['exam_id', 'like', '%'.$exam_id.'%'], ['section_id', '=', $section_id], ['level', '=', $level]])->orderByRaw("RAND()")->take(10)->get()->toArray();

        if (count($fetch_question_details) > 0) {
            foreach ($fetch_question_details as $key => $value) {
                if ($value['option_type'] == 'mcq') {
                    $question_type = $value['question_type'];
                    if ($question_type == 'image') {
                        $question_url = url('/') . "/upload/question_file/resize/" . $value['question'];
                        $question = "<img src='".$question_url."' alt='' />";
                    }
                    if ($question_type == 'text') {
                        $question = $value['question'];
                    }

                    $option = unserialize($value['answer']);
                    $option['optionA_val'] = '1';
                    $option['optionB_val'] = '2';
                    $option['optionC_val'] = '3';
                    $option['optionD_val'] = '4';
                    $option['optionE_val'] = '5';

                    $option_image_link = url('/') . "/upload/answers_file/resize/";
                    
                    $correct_answer = count(unserialize($value['correct_answer']));
                    if ($correct_answer > 1) {
                        $answer_type = 'multiple';
                    } else {
                        $answer_type = 'single';
                    }

                    if (!empty($value['explanation_file'])) {
                        $explanation_file_link = url('/') . "/upload/explanation_file/resize/" . $value['explanation_file'];
                    } else {
                        $explanation_file_link = "N/A";
                    }
                }
                else if ($value['option_type'] == 'numeric') {
                    $question_type = $value['question_type'];
                    if ($question_type == 'image') {
                        $question = url('/') . "/upload/question_file/resize/" . $value['question'];
                    }
                    if ($question_type == 'text') {
                        $question = $value['question'];
                    }

                    if (!empty($value['explanation_file'])) {
                        $explanation_file_link = url('/') . "/upload/explanation_file/resize/" . $value['explanation_file'];
                    } else {
                        $explanation_file_link = "N/A";
                    }

                    $option = "";
                    $option_image_link = "";
                    $answer_type = '';
                }

                $question_list[] = array(
                    'question_id' => $value['id'],
                    'question_type' => $value['question_type'],
                    'question' => $question,
                    'option_type' => $value['option_type'],
                    'option' => $option,
                    'option_image_link' => $option_image_link,
                    'answer_type' => $answer_type,
                    'explanation_details' => $value['explanation_details'],
                    'explanation_file_link' => $explanation_file_link
                );
            }

            $fetch_timer = ExamTimer::all()->toArray();
            if ($subject_id == 0 && $area_id == 0) {
                $test_timer = $fetch_timer[0]['exam_test'];
            } else if ($subject_id == 0 && $area_id != 0) {
                $test_timer = $fetch_timer[0]['subject_test'];
            } else if ($subject_id != 0 && $area_id != 0) {
                $test_timer = $fetch_timer[0]['area_test'];
            }
            return response()->json(['status_code' => 200, 'msg' => 'Success', 'data' => $question_list, 'time' => $test_timer]);
        } else {
            return response()->json(['status_code' => 404, 'msg' => 'No question found.']);
        }
    }

    public function fetch_user_ans(Request $request) {
        $user = JWTAuth::toUser($request->token);
        $user_id = $user['id'];

        $exam_id = ($request->exam_id != '') ? $request->exam_id : 0;
        $subject_id = ($request->subject_id != '') ? $request->subject_id : 0;
        $area_id = ($request->area_id != '') ? $request->area_id : 0;
        $section_id = ($request->section_id != '') ? $request->section_id : 0;
        $level = ($request->level != '') ? $request->level : 0;

        if ($section_id == 0) {
            $limit = 10;
        } else {
            $limit = 5;
        }

        $fetch_user_ans_details = UserExam::where([['student_id', '=', $user_id], ['exam_id', '=', $exam_id], ['subject_id', '=', $subject_id], ['area_id', '=', $area_id], ['section_id', '=', $section_id], ['level', '=', $level]])->orderBy('id', 'desc')->take($limit)->get()->toArray();

        $i = 0;
        $k = 0;
        $total_no_of_question = '';
        foreach ($fetch_user_ans_details as $key => $value) {
            $fetch_question_list = QuestionAnswer::where([['exam_id', 'like', '%'.$exam_id.'%'], ['subject_id', '=', $subject_id], ['area_id', '=', $area_id], ['section_id', '=', $section_id], ['level', '=', $level], ['id', '=', $value['question_id']]])->get()->toArray();
            if ($fetch_question_list[0]['option_type'] == 'mcq') {
                $user_answer = unserialize($value['user_answer']);
                $correct_answer = unserialize($fetch_question_list[0]['correct_answer']);
                if ($correct_answer == $user_answer) {
                    $i++;
                }
            } else if ($fetch_question_list[0]['option_type'] == 'numeric') {
                $numeric_answer = trim($value['numeric_ans']);
                $correct_answer = trim($fetch_question_list[0]['numeric_answer']);
                if ($correct_answer == $numeric_answer) {
                    $i++;
                }
            }
            $total_correct_answer = $i;
            $k++;
        }
        $total_no_of_question = $k;
        $marks = ($total_correct_answer / $total_no_of_question) * 100 . '%';

        $fetch_user_existing_marks = UserMarks::where([['student_id', '=', $user_id], ['exam_id', '=', $exam_id], ['subject_id', '=', $subject_id], ['area_id', '=', $area_id], ['section_id', '=', $section_id], ['level', '=', $level]])->orderBy('id', 'desc')->take(1)->get()->toArray();

        if (count($fetch_user_existing_marks) > 0) {
            $existing_marks = $fetch_user_existing_marks[0]['percentile'];
            $existing_correct_ans = $fetch_user_existing_marks[0]['total_correct_ans'];
        } else {
            $existing_marks = '';
            $existing_correct_ans = 0;
        }

        if ($level == 1) {
            $exam_rating = "200";
        } else if ($level == 2) {
            $exam_rating = "400";
        } else if ($level == 3) {
            $exam_rating = "600";
        } else if ($level == 4) {
            $exam_rating = "700";
        } else if ($level == 5) {
            $exam_rating = "800";
        } else {
            $exam_rating = "0";
        }

        if ($total_correct_answer > $existing_correct_ans && $marks != $existing_marks) {
            $add = new UserMarks();
            $add->student_id = $user_id;
            $add->exam_id = $exam_id;
            $add->area_id = $area_id;
            $add->subject_id = $subject_id;
            $add->section_id = $section_id;
            $add->level = $level;
            $add->percentile = $marks;
            $add->total_correct_ans = $total_correct_answer;
            $add->exam_rating = $exam_rating;
            $add->save();
        }

        return response()->json(['status_code' => 200, 'total_no_of_question' => $total_no_of_question, 'total_correct_answer' => $total_correct_answer, 'marks' => $marks]);
    }

    public function fetch_exam_question(Request $request) {
        $user = JWTAuth::toUser($request->token);
        $user_id = $user['id'];

        $exam_id = ($request->exam_id != '') ? $request->exam_id : 0;
        $area_subject_test_lock = array();

        $all_subject_by_exam = SubjectExam::where('exam_id', $exam_id)->get()->toArray();
        foreach ($all_subject_by_exam as $key => $value) {
            $fetch_user_marks_details_by_subject = UserMarks::where([['student_id', '=', $user_id], ['exam_id', '=', $exam_id], ['subject_id', '=', $value['subject_id']], ['level', '=', '7']])->orderBy('id', 'desc')->take(1)->get()->toArray();
            if (count($fetch_user_marks_details_by_subject) > 0) {
                $fetch_subject_correct_ans = $fetch_user_marks_details_by_subject[0]['total_correct_ans'];
            } else {
                $fetch_subject_correct_ans = 0;
            }
            if ($fetch_subject_correct_ans >= 6) {
                array_push($area_subject_test_lock, 0);
            } else {
                array_push($area_subject_test_lock, 1);
            }
        }

        foreach ($area_subject_test_lock as $lock_key => $lock_value) {
            if ($lock_value == 1) {
                $main_test_lock = '1';
                break;
            } else {
                $main_test_lock = '0';
            }
        }

        if ($main_test_lock == '0') {
            $question_list = array();
            $fetch_question_details = ExamQuestionAnswer::where('exam_id', '=', $exam_id)->orderByRaw("RAND()")->take(20)->get()->toArray();
            if (count($fetch_question_details) > 0) {
                foreach ($fetch_question_details as $key => $value) {
                    if ($value['option_type'] == 'mcq') {
                        $question_type = $value['question_type'];
                        if ($question_type == 'image') {
                            $question_url = url('/') . "/upload/question_file/resize/" . $value['question'];
                            $question = "<img src='".$question_url."' alt='' />";
                        }
                        if ($question_type == 'text') {
                            $question = $value['question'];
                        }

                        $option = unserialize($value['answer']);
                        $option['optionA_val'] = '1';
                        $option['optionB_val'] = '2';
                        $option['optionC_val'] = '3';
                        $option['optionD_val'] = '4';
                        $option['optionE_val'] = '5';

                        $option_image_link = url('/') . "/upload/answers_file/resize/";
                        
                        $correct_answer = count(unserialize($value['correct_answer']));
                        if ($correct_answer > 1) {
                            $answer_type = 'multiple';
                        } else {
                            $answer_type = 'single';
                        }

                        if (!empty($value['explanation_file'])) {
                            $explanation_file_link = url('/') . "/upload/explanation_file/resize/" . $value['explanation_file'];
                        } else {
                            $explanation_file_link = "N/A";
                        }
                    }
                    else if ($value['option_type'] == 'numeric') {
                        $question_type = $value['question_type'];
                        if ($question_type == 'image') {
                            $question = url('/') . "/upload/question_file/resize/" . $value['question'];
                        }
                        if ($question_type == 'text') {
                            $question = $value['question'];
                        }

                        if (!empty($value['explanation_file'])) {
                            $explanation_file_link = url('/') . "/upload/explanation_file/resize/" . $value['explanation_file'];
                        } else {
                            $explanation_file_link = "N/A";
                        }

                        $option = "";
                        $option_image_link = "";
                        $answer_type = '';
                    }

                    $question_list[] = array(
                        'question_id' => $value['id'],
                        'question_type' => $value['question_type'],
                        'question' => $question,
                        'option_type' => $value['option_type'],
                        'option' => $option,
                        'option_image_link' => $option_image_link,
                        'answer_type' => $answer_type,
                        'explanation_details' => $value['explanation_details'],
                        'explanation_file_link' => $explanation_file_link
                    );
                }

                $fetch_timer = ExamTimer::all()->toArray();
                $test_timer = $fetch_timer[0]['exam_test'];
                return response()->json(['status_code' => 200, 'msg' => 'Success', 'data' => $question_list, 'time' => $test_timer]);
            } else {
                return response()->json(['status_code' => 404, 'msg' => 'No question found.']);
            }
        } else {
            return response()->json(['status_code' => 500, 'msg' => 'You are not eligible to take this test!']);
        }
    }

    public function fetch_user_exam_ans(Request $request) {
        $user = JWTAuth::toUser($request->token);
        $user_id = $user['id'];
        $exam_id = ($request->exam_id != '') ? $request->exam_id : 0;
        $fetch_user_ans_details = UserExamAnswer::where([['student_id', '=', $user_id], ['exam_id', '=', $exam_id]])->orderBy('id', 'desc')->take(20)->get()->toArray();

        $i = 0;
        $k = 0;
        $total_no_of_question = '';
        foreach ($fetch_user_ans_details as $key => $value) {
            $fetch_question_list = ExamQuestionAnswer::where([['exam_id', '=', $exam_id], ['id', '=', $value['question_id']]])->get()->toArray();
            if ($fetch_question_list[0]['option_type'] == 'mcq') {
                $user_answer = unserialize($value['user_answer']);
                $correct_answer = unserialize($fetch_question_list[0]['correct_answer']);
                if ($correct_answer == $user_answer) {
                    $i++;
                }
            } else if ($fetch_question_list[0]['option_type'] == 'numeric') {
                $numeric_answer = trim($value['numeric_ans']);
                $correct_answer = trim($fetch_question_list[0]['numeric_answer']);
                if ($correct_answer == $numeric_answer) {
                    $i++;
                }
            }
            $total_correct_answer = $i;
            $k++;
        }
        $total_no_of_question = $k;
        $marks = ($total_correct_answer / $total_no_of_question) * 100 . '%';

        $fetch_user_existing_marks = UserExamMarks::where([['student_id', '=', $user_id], ['exam_id', '=', $exam_id]])->orderBy('id', 'desc')->take(1)->get()->toArray();

        if (count($fetch_user_existing_marks) > 0) {
            $existing_marks = $fetch_user_existing_marks[0]['percentile'];
            $existing_correct_ans = $fetch_user_existing_marks[0]['total_correct_ans'];
        } else {
            $existing_marks = '';
            $existing_correct_ans = 0;
        }

        if ($total_correct_answer > $existing_correct_ans && $marks != $existing_marks) {
            $add = new UserExamMarks();
            $add->student_id = $user_id;
            $add->exam_id = $exam_id;
            $add->total_correct_ans = $total_correct_answer;
            $add->percentile = $marks;
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
                $image = url('/') .'/upload/profile_image/original/'. $user['image'];
            }
            $user_point = $user['current_rating'];
    		if ($edit->save()) {
                return response()->json([
                    'status_code' => 200,
                    'msg' => 'Your have successfully acivate your account.',
                    'token' => $token,
                    'first_name' => ucfirst($user['first_name']),
                    'last_name' => ucfirst($user['last_name']),
                    'image' => $image,
                    'exam_id' => $exam_id,
                    'rating' => $user_point
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

        $review_list = array();

        $xxx = UserExam::where([['student_id', '=', $user_id], ['exam_id', '=', $exam_id], ['subject_id', '=', $subject_id], ['area_id', '=', $area_id], ['section_id', '=', $section_id], ['level', '=', $level]])->get();
        $xxx_count = count($xxx);
        $sss = $xxx_count - 5;

        $fetch_user_ans_details = UserExam::where([['student_id', '=', $user_id], ['exam_id', '=', $exam_id], ['subject_id', '=', $subject_id], ['area_id', '=', $area_id], ['section_id', '=', $section_id], ['level', '=', $level]])->skip($sss)->take(5)->get()->toArray();

        if (count($fetch_user_ans_details) > 0) {
            foreach ($fetch_user_ans_details as $key => $value) {
                $fetch_question_details = QuestionAnswer::where([['subject_id', '=', $subject_id], ['area_id', '=', $area_id], ['status', '=', '1'], ['section_id', '=', $section_id], ['exam_id', 'like', '%'.$exam_id.'%'], ['level', '=', $level], ['id', '=', $value['question_id']]])->get()->toArray();
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

                    $user_answer = unserialize($value['user_answer']);
                    $user_answer = $user_answer[0];
                    $correct_answer = unserialize($fetch_question_details[0]['correct_answer']);
                    $correct_answer = $correct_answer[0];
                }
                else if ($fetch_question_details[0]['option_type'] == 'numeric') {
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

                    $user_answer = $value['numeric_ans'];
                    $correct_answer = $fetch_question_details[0]['numeric_answer'];

                    $option = "";
                    $option_image_link = "";
                    $answer_type = '';
                }
                $review_list[] = array(
                    'question_id' => $fetch_question_details[0]['id'],
                    'question_type' => $fetch_question_details[0]['question_type'],
                    'question' => $question,
                    'option_type' => $fetch_question_details[0]['option_type'],
                    'option' => $option,
                    'option_image_link' => $option_image_link,
                    'answer_type' => $answer_type,
                    'user_answer' => $user_answer,
                    'correct_answer' => $correct_answer,
                    'explanation_details' => $fetch_question_details[0]['explanation_details'],
                    'explanation_file_link' => $explanation_file_link
                );
            }
            return response()->json(['status_code' => 200, 'msg' => 'Success', 'data' => $review_list]);
        } else {
            return response()->json(['status_code' => 404, 'msg' => 'No reviews found.']);
        }
    }

    public function review_area_exam(Request $request) {
        $user = JWTAuth::toUser($request->token);
        $user_id = $user['id'];

        $exam_id = ($request->exam_id != '') ? $request->exam_id : 0;
        $subject_id = ($request->subject_id != '') ? $request->subject_id : 0;
        $area_id = ($request->area_id != '') ? $request->area_id : 0;
        $section_id = 0;
        $level = ($request->level != '') ? $request->level : 0;

        $review_list = array();

        $xxx = UserExam::where([['student_id', '=', $user_id], ['exam_id', '=', $exam_id], ['subject_id', '=', $subject_id], ['area_id', '=', $area_id], ['section_id', '=', $section_id], ['level', '=', $level]])->get();
        $xxx_count = count($xxx);
        $sss = $xxx_count - 10;

        $fetch_user_ans_details = UserExam::where([['student_id', '=', $user_id], ['exam_id', '=', $exam_id], ['subject_id', '=', $subject_id], ['area_id', '=', $area_id], ['section_id', '=', $section_id], ['level', '=', $level]])->skip($sss)->take(10)->get()->toArray();

        if (count($fetch_user_ans_details) > 0) {
            foreach ($fetch_user_ans_details as $key => $value) {
                $fetch_question_details = QuestionAnswer::where([['subject_id', '=', $subject_id], ['area_id', '=', $area_id], ['status', '=', '1'], ['exam_id', 'like', '%'.$exam_id.'%'], ['section_id', '=', $section_id], ['level', '=', $level], ['id', '=', $value['question_id']]])->get()->toArray();
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

                    $user_answer = unserialize($fetch_user_ans_details[0]['user_answer']);
                    $user_answer = $user_answer[0];
                    $correct_answer = unserialize($fetch_question_details[0]['correct_answer']);
                    $correct_answer = $correct_answer[0];
                }
                else if ($fetch_question_details[0]['option_type'] == 'numeric') {
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

                    $user_answer = $value['numeric_ans'];
                    $correct_answer = $fetch_question_details[0]['numeric_answer'];

                    $option = "";
                    $option_image_link = "";
                    $answer_type = '';
                }

                $review_list[] = array(
                    'question_id' => $fetch_question_details[0]['id'],
                    'question_type' => $fetch_question_details[0]['question_type'],
                    'question' => $question,
                    'option_type' => $fetch_question_details[0]['option_type'],
                    'option' => $option,
                    'option_image_link' => $option_image_link,
                    'answer_type' => $answer_type,
                    'user_answer' => $user_answer,
                    'correct_answer' => $correct_answer,
                    'explanation_details' => $fetch_question_details[0]['explanation_details'],
                    'explanation_file_link' => $explanation_file_link
                );
            }
            return response()->json(['status_code' => 200, 'msg' => 'Success', 'data' => $review_list]);
        } else {
            return response()->json(['status_code' => 404, 'msg' => 'No reviews found.']);
        }
    }

    public function review_exam_answer(Request $request) {
        $user = JWTAuth::toUser($request->token);
        $user_id = $user['id'];

        $exam_id = ($request->exam_id != '') ? $request->exam_id : 0;
        $review_list = array();

        $xxx = UserExamAnswer::where([['student_id', '=', $user_id], ['exam_id', '=', $exam_id]])->get();
        $xxx_count = count($xxx);
        $sss = $xxx_count - 20;

        $fetch_user_ans_details = UserExamAnswer::where([['student_id', '=', $user_id], ['exam_id', '=', $exam_id]])->skip($sss)->take(20)->get()->toArray();
        if (count($fetch_user_ans_details) > 0) {
            foreach ($fetch_user_ans_details as $key => $value) {
                $fetch_question_details = ExamQuestionAnswer::where([['status', '=', '1'], ['exam_id', '=', $exam_id], ['id', '=', $value['question_id']]])->get()->toArray();
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

                    $user_answer = unserialize($fetch_user_ans_details[0]['user_answer']);
                    $user_answer = $user_answer[0];
                    $correct_answer = unserialize($fetch_question_details[0]['correct_answer']);
                    $correct_answer = $correct_answer[0];
                }
                else if ($fetch_question_details[0]['option_type'] == 'numeric') {
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

                    $user_answer = $value['numeric_ans'];
                    $correct_answer = $fetch_question_details[0]['numeric_answer'];

                    $option = "";
                    $option_image_link = "";
                    $answer_type = '';
                }

                $review_list[] = array(
                    'question_id' => $fetch_question_details[0]['id'],
                    'question_type' => $fetch_question_details[0]['question_type'],
                    'question' => $question,
                    'option_type' => $fetch_question_details[0]['option_type'],
                    'option' => $option,
                    'option_image_link' => $option_image_link,
                    'answer_type' => $answer_type,
                    'user_answer' => $user_answer,
                    'correct_answer' => $correct_answer,
                    'explanation_details' => $fetch_question_details[0]['explanation_details'],
                    'explanation_file_link' => $explanation_file_link
                );
            }
            return response()->json(['status_code' => 200, 'msg' => 'Success', 'data' => $review_list]);
        } else {
            return response()->json(['status_code' => 404, 'msg' => 'No reviews found.']);
        }
    }

    public function calculate_user_rating(Request $request) {
        $user = JWTAuth::toUser($request->token);
        $user_id = $user['id'];

        $exam_id = ($request->exam_id != '') ? $request->exam_id : 0;
        $subject_id = ($request->subject_id != '') ? $request->subject_id : 0;
        $area_id = ($request->area_id != '') ? $request->area_id : 0;
        $section_id = ($request->section_id != '') ? $request->section_id : 0;
        $level = ($request->level != '') ? $request->level : 0;

        $rating = '';
        $numerator = 0;
        $denominator = 0;

        if ($level == 1 || $level == 2 || $level == 3 || $level == 4 || $level == 5) {
            $fetch_user_marks_level1_details = UserMarks::where([['student_id', '=', $user_id], ['exam_id', '=', $exam_id], ['subject_id', '=', $subject_id], ['area_id', '=', $area_id], ['section_id', '=', $section_id], ['level', '=', '1']])->orderBy('id', 'desc')->take(1)->get()->toArray();
            $fetch_user_marks_level2_details = UserMarks::where([['student_id', '=', $user_id], ['exam_id', '=', $exam_id], ['subject_id', '=', $subject_id], ['area_id', '=', $area_id], ['section_id', '=', $section_id], ['level', '=', '2']])->orderBy('id', 'desc')->take(1)->get()->toArray();
            $fetch_user_marks_level3_details = UserMarks::where([['student_id', '=', $user_id], ['exam_id', '=', $exam_id], ['subject_id', '=', $subject_id], ['area_id', '=', $area_id], ['section_id', '=', $section_id], ['level', '=', '3']])->orderBy('id', 'desc')->take(1)->get()->toArray();
            $fetch_user_marks_level4_details = UserMarks::where([['student_id', '=', $user_id], ['exam_id', '=', $exam_id], ['subject_id', '=', $subject_id], ['area_id', '=', $area_id], ['section_id', '=', $section_id], ['level', '=', '4']])->orderBy('id', 'desc')->take(1)->get()->toArray();
            $fetch_user_marks_level5_details = UserMarks::where([['student_id', '=', $user_id], ['exam_id', '=', $exam_id], ['subject_id', '=', $subject_id], ['area_id', '=', $area_id], ['section_id', '=', $section_id], ['level', '=', '5']])->orderBy('id', 'desc')->take(1)->get()->toArray();

            if (count($fetch_user_marks_level1_details) > 0) {
                $no_of_crct_ans_level1 = $fetch_user_marks_level1_details[0]['total_correct_ans'];
                $crct_atmt_pnt_level1 = $this->get_correct_attempt_point(1, $no_of_crct_ans_level1);
                $numerator1 = 200 * $no_of_crct_ans_level1 * $crct_atmt_pnt_level1;
                $denominator1 = 200 * $crct_atmt_pnt_level1;
                $user_rating_level1 = (round($numerator1 / $denominator1) * 10);
            }
            if (count($fetch_user_marks_level2_details) > 0) {
                $no_of_crct_ans_level2 = $fetch_user_marks_level2_details[0]['total_correct_ans'];
                $crct_atmt_pnt_level2 = $this->get_correct_attempt_point(2, $no_of_crct_ans_level2);
                $numerator2 = 400 * $no_of_crct_ans_level2 * $crct_atmt_pnt_level2;
                $denominator2 = 400 * $crct_atmt_pnt_level2;
                $user_rating_level2 = $user_rating_level1 + (round($numerator2 / $denominator2) * 10);
            }
            if (count($fetch_user_marks_level3_details) > 0) {
                $no_of_crct_ans_level3 = $fetch_user_marks_level3_details[0]['total_correct_ans'];
                $crct_atmt_pnt_level3 = $this->get_correct_attempt_point(3, $no_of_crct_ans_level3);
                $numerator3 = 600 * $no_of_crct_ans_level3 * $crct_atmt_pnt_level3;
                $denominator3 = 600 * $crct_atmt_pnt_level3;
                $user_rating_level3 = $user_rating_level1 + $user_rating_level2 + (round($numerator3 / $denominator3) * 10);
            }
            if (count($fetch_user_marks_level4_details) > 0) {
                $no_of_crct_ans_level4 = $fetch_user_marks_level4_details[0]['total_correct_ans'];
                $crct_atmt_pnt_level4 = $this->get_correct_attempt_point(4, $no_of_crct_ans_level4);
                $numerator4 = 700 * $no_of_crct_ans_level4 * $crct_atmt_pnt_level4;
                $denominator4 = 700 * $crct_atmt_pnt_level4;
                $user_rating_level4 = $user_rating_level1 + $user_rating_level2 + $user_rating_level3 + (round($numerator4 / $denominator4) * 10);
            }
            if (count($fetch_user_marks_level5_details) > 0) {
                $no_of_crct_ans_level5 = $fetch_user_marks_level5_details[0]['total_correct_ans'];
                $crct_atmt_pnt_level5 = $this->get_correct_attempt_point(5, $no_of_crct_ans_level5);
                $numerator5 = 800 * $no_of_crct_ans_level5 * $crct_atmt_pnt_level5;
                $denominator5 = 800 * $crct_atmt_pnt_level5;
                $user_rating_level5 = $user_rating_level1 + $user_rating_level2 + $user_rating_level3 + $user_rating_level4 + (round($numerator5 / $denominator5) * 10);
            }

            if ($level == 1) {
                $rating = $user_rating_level1;
            } else if ($level == 2) {
                $rating = $user_rating_level2;
            } else if ($level == 3) {
                $rating = $user_rating_level3;
            } else if ($level == 4) {
                $rating = $user_rating_level4;
            } else if ($level == 5) {
                $rating = $user_rating_level5;
            }

            $add = new StudentRatingLog();
            $add->student_id = $user_id;
            $add->exam_id = $exam_id;
            $add->area_id = $area_id;
            $add->subject_id = $subject_id;
            $add->section_id = $section_id;
            $add->level = $level;
            $add->rating = $rating;
            if ($add->save()) {
                $student = Student::find($user_id);
                $student->current_rating = $rating;
                if ($student->save()) {
                    $student_details = $student->toArray();
                    if ($student_details['image'] == '') {
                        $image = url('/') .'/upload/profile_image/default.png';
                    } else {
                        $image = url('/') .'/upload/profile_image/original/'. $student_details['image'];
                    }
                    $user_firstname = ucfirst($student_details['first_name']);
                    $user_lastname = ucfirst($student_details['last_name']);
                    $user_point = $student_details['current_rating'];

                    return response()->json(['status_code' => 200, 'msg' => 'Success', 'first_name' => $user_firstname, 'last_name' => $user_lastname, 'image' => $image, 'rating' => $user_point]);
                } else {
                    return response()->json(['status_code' => 500, 'msg' => 'Error']);
                }
            } else {
                return response()->json(['status_code' => 500, 'msg' => 'Error']);
            }
        } else {
            $student = Student::find($user_id);
            $student_details = $student->toArray();
            if ($student_details['image'] == '') {
                $image = url('/') .'/upload/profile_image/default.png';
            } else {
                $image = url('/') .'/upload/profile_image/original/'. $student_details['image'];
            }
            $user_firstname = ucfirst($student_details['first_name']);
            $user_lastname = ucfirst($student_details['last_name']);
            $user_point = $student_details['current_rating'];
            return response()->json(['status_code' => 200, 'msg' => 'Success', 'first_name' => $user_firstname, 'last_name' => $user_lastname, 'image' => $image, 'rating' => $user_point]);
        }
    }

    public function get_correct_attempt_point($level, $no_of_crct_ans) {
        if ($level == 1) {
            if ($no_of_crct_ans <= 3) {
                $crct_atmt_pnt = 1;
            } else if ($no_of_crct_ans == 4) {
                $crct_atmt_pnt = 5;
            } else if ($no_of_crct_ans >= 5) {
                $crct_atmt_pnt = 8;
            }
        } else if ($level == 2) {
            if ($no_of_crct_ans <= 3) {
                $crct_atmt_pnt = 16;
            } else if ($no_of_crct_ans == 4) {
                $crct_atmt_pnt = 24;
            } else if ($no_of_crct_ans >= 5) {
                $crct_atmt_pnt = 30;
            }
        } else if ($level == 3) {
            if ($no_of_crct_ans <= 3) {
                $crct_atmt_pnt = 60;
            } else if ($no_of_crct_ans == 4) {
                $crct_atmt_pnt = 76;
            } else if ($no_of_crct_ans >= 5) {
                $crct_atmt_pnt = 88;
            }
        } else if ($level == 4) {
            if ($no_of_crct_ans <= 3) {
                $crct_atmt_pnt = 176;
            } else if ($no_of_crct_ans == 4) {
                $crct_atmt_pnt = 208;
            } else if ($no_of_crct_ans >= 5) {
                $crct_atmt_pnt = 232;
            }
        } else if ($level == 5) {
            if ($no_of_crct_ans <= 3) {
                $crct_atmt_pnt = 696;
            } else if ($no_of_crct_ans == 4) {
                $crct_atmt_pnt = 792;
            } else if ($no_of_crct_ans >= 5) {
                $crct_atmt_pnt = 864;
            }
        }
        return $crct_atmt_pnt;
    }
}

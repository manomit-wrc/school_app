<?php
namespace App\Http\Controllers;

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Student;
use App\Exam;
use App\Area;
use App\Subject;
use App\Section;
use App\StudyMat;
use App\StudyMatSampleQues;
use App\UserExam;
use App\UserExamAnswer;
use JWTAuth;
use JWTAuthException;
use Validator;
use Config;
use Illuminate\Support\Facades\Mail;
use App\Mail\Registration;
use App\QuestionAnswer;
use App\ExamQuestionAnswer;
use App\SubjectExam;
use App\StudentStudyMatLog;
use App\UserMarks;
use App\StudyMatVideo;
use App\StudyMatTheory;
use App\Tips;

use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;

class StudentController extends Controller
{
    public function registration(Request $request) {
    	$validator = Validator::make($request->all(),[
    		'email' => 'required|email|unique:students,email',
    		'password' => 'required',
    		'mobile_no' => 'required|max:10|min:10|regex:/[0-9]{10}/'
		],[
			'email.required' => 'Please enter email id'
		]);
		if ($validator->fails()) {
            return response()->json(['error' => true,
                'msg' => $validator->messages()->first(),
                'status_code' => 500]);
        } else {
            $student = new Student();
        	$student->first_name = $request->first_name;
            $student->last_name = $request->last_name;
        	$student->email = $request->email;
        	$student->password = bcrypt($request->password);
        	$student->mobile_no = $request->mobile_no;
            $student->city = $request->city;
            $student->country = $request->country;
            $student->device_id = $request->device_id;
            $eaxm_name = trim($request->exam_id);
            $fetch_exam_id = Exam::where('code','like',"%".$eaxm_name."%")->get()->toArray();
            $exam_id = $fetch_exam_id[0]['id'];
            $student->exam_id = $exam_id;
        	if ($student->save()) {
                $otp = rand(1000,5000);
                $user_name = $request->username;
                $user_id = $student->id;
                $edit = Student::find($user_id);
                $edit->otp = $otp;
                if ($edit->save()) {
                    try{
                        Mail::to($request->email)->send(new Registration($otp,$user_name));
                        return response()->json(['error' => false, 'msg' => 'Registration has been successfully completed & OTP send to the user', 'status_code' => 200]);
                    } catch(\Exception $e) {
                        return response()->json(['status_code' => 500, 'msg' => 'error']);
                    }
                }
        	}
        }
    }

    public function login(Request $request) {
        Config::set('tymon.jwt.provider.jwt', '\App\Student');
        $credentials = $request->only('email', 'password');
        $token = null;
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['msg' => 'Invalid Username or password', 'status_code' => 404]);
            }
        } catch (JWTAuthException $e) {
            return response()->json(['msg' => 'Failed to create token', 'status_code' => 500]);
        }
        $user = JWTAuth::toUser($token);
        if ($user->image == '') {
            $image = url('/') .'/upload/profile_image/default.png';
        } else {
            $image = url('/') .'/upload/profile_image/original/'. $user->image;
        }
        if ($user->status == 0) {
            return response()->json(['msg' => 'Account not activated.', 'status_code' => 404]);
        } else {
            $user_device_update = Student::find($user['id']);
            $user_device_update->device_id = $request->device_id;
            $user_device_update->save();

            return response()->json([
                'msg' => 'Successfully login',
                'status_code' => 200,
                'token' => $token,
                'first_name' => ucfirst($user['first_name']),
                'last_name' => ucfirst($user['last_name']),
                'image' => $image,
                'exam_id' => $user['exam_id'],
                'rating' => $user['rating']
            ]);
        }
    }

    public function changepass(Request $request) {
        $user = JWTAuth::toUser($request->header('token'));
        $student = Student::find($user['id']);
        $student->password = bcrypt($request->password);
        if ($student->save()) {
            return response()->json(['msg' => 'Success', 'status_code' => 200]);
        } else {
            return response()->json(['msg' => 'Failure', 'status_code' => 500]);
        }
    }

    public function get_all_exam(Request $request) {
        $exam_list = Exam::all();
        return response()->json(['msg' => 'Success', 'status_code' => 200, 'data' => $exam_list]);
    }

    public function get_exam_by_exam_id(Request $request) {
        $exam_id = $request->exam_id;
        $exam_details = Exam::find($exam_id);
        if ($exam_details) {
            return response()->json(['msg' => 'Success', 'status_code' => 200, 'data' => $exam_details]);
        } else {
            return response()->json(['msg' => 'No exam available', 'status_code' => 404]);
        }
    }

    public function get_subject(Request $request) {
        $exam_id = $request->exam_id;
        $all_subject = SubjectExam::with('subject')->where('exam_id', $exam_id)->orderBy('id', 'desc')->get()->toArray();
        if ($all_subject) {
            return response()->json(['msg' => 'Success', 'status_code' => 200, 'data' => $all_subject]);
        } else {
            return response()->json(['msg' => 'No subject available', 'status_code' => 404]);
        }
    }

    public function get_area(Request $request) {
        $user = JWTAuth::toUser($request->token);
        $user_id = $user['id'];

        $exam_id = $request->exam_id;
        $subject_id = $request->subject_id;

        $area_test_lock_entry = array();
        $i = 1;
        $area_details = Area::where([['exam_id', '=', $exam_id], ['subject_id', '=', $subject_id]])->orderBy('sort_order', 'asc')->get()->toArray();
        foreach ($area_details as $key => $value) {
            $area_section_lock_entry = array();
            $section_details = array();
            $area_id = $value['id'];
            $section_id = 0;
            if ($i == 1) {
                $area_lock = '0';
            } else {
                $fetch_area_user_marks_details = UserMarks::where([['student_id', '=', $user_id], ['exam_id', '=', $exam_id], ['subject_id', '=', $subject_id], ['area_id', '=', $pre_area_id], ['level', '=', '6']])->orderBy('id', 'desc')->take(1)->get()->toArray();
                if (count($fetch_area_user_marks_details) > 0) {
                    $fetch_area_correct_ans = $fetch_area_user_marks_details[0]['total_correct_ans'];
                } else {
                    $fetch_area_correct_ans = 0;
                }
                if ($fetch_area_correct_ans >= 6) {
                    $area_lock = '0';
                } else {
                    $area_lock = '1';
                }
            }
            $area_details[$key]['area_lock'] = $area_lock;
            $fetch_sections = Section::where('area_id', $area_id)->get()->toArray();
            foreach ($fetch_sections as $sec_key => $sec_value) {
                $section_details[] = $sec_value;
                if ($i == 1) {
                    $section_lock = '0';
                    $section_id = $sec_value['id'];
                } else if ($area_lock == '1') {
                    $section_lock = '1';
                    $section_id = $sec_value['id'];
                } else if ($area_id != $pre_area_id) {
                    $section_lock = '0';
                    $section_id = $sec_value['id'];
                } else {
                    $fetch_user_exam_level1_details = UserExam::where([['exam_id', '=', $exam_id], ['subject_id', '=', $subject_id], ['section_id', '=', $section_id], ['level', '=', '1']])->get()->toArray();
                    $fetch_user_exam_level1_count = count($fetch_user_exam_level1_details);
                    $fetch_user_exam_level2_details = UserExam::where([['exam_id', '=', $exam_id], ['subject_id', '=', $subject_id], ['section_id', '=', $section_id], ['level', '=', '2']])->get()->toArray();
                    $fetch_user_exam_level2_count = count($fetch_user_exam_level2_details);
                    $fetch_user_exam_level3_details = UserExam::where([['exam_id', '=', $exam_id], ['subject_id', '=', $subject_id], ['section_id', '=', $section_id], ['level', '=', '3']])->get()->toArray();
                    $fetch_user_exam_level3_count = count($fetch_user_exam_level3_details);
                    if ($fetch_user_exam_level1_count > 0 && $fetch_user_exam_level2_count > 0 && $fetch_user_exam_level3_count > 0) {
                        $section_lock = '0';
                        $section_id = $sec_value['id'];
                    } else {
                        $section_lock = '1';
                        $section_id = $sec_value['id'];
                    }
                }
                $fetch_user_marks_level1_details = UserMarks::where([['student_id', '=', $user_id], ['exam_id', '=', $exam_id], ['subject_id', '=', $subject_id], ['area_id', '=', $area_id], ['section_id', '=', $section_id], ['level', '=', '1']])->orderBy('id', 'desc')->take(1)->get()->toArray();
                if (count($fetch_user_marks_level1_details) > 0) {
                    $fetch_correct_ans_level1 = $fetch_user_marks_level1_details[0]['total_correct_ans'];
                } else {
                    $fetch_correct_ans_level1 = 0;
                }
                $fetch_user_marks_level2_details = UserMarks::where([['student_id', '=', $user_id], ['exam_id', '=', $exam_id], ['subject_id', '=', $subject_id], ['area_id', '=', $area_id], ['section_id', '=', $section_id], ['level', '=', '2']])->orderBy('id', 'desc')->take(1)->get()->toArray();
                if (count($fetch_user_marks_level2_details) > 0) {
                    $fetch_correct_ans_level2 = $fetch_user_marks_level2_details[0]['total_correct_ans'];
                } else {
                    $fetch_correct_ans_level2 = 0;
                }
                $fetch_user_marks_level3_details = UserMarks::where([['student_id', '=', $user_id], ['exam_id', '=', $exam_id], ['subject_id', '=', $subject_id], ['area_id', '=', $area_id], ['section_id', '=', $section_id], ['level', '=', '3']])->orderBy('id', 'desc')->take(1)->get()->toArray();
                if (count($fetch_user_marks_level3_details) > 0) {
                    $fetch_correct_ans_level3 = $fetch_user_marks_level3_details[0]['total_correct_ans'];
                } else {
                    $fetch_correct_ans_level3 = 0;
                }
                $fetch_user_marks_level4_details = UserMarks::where([['student_id', '=', $user_id], ['exam_id', '=', $exam_id], ['subject_id', '=', $subject_id], ['area_id', '=', $area_id], ['section_id', '=', $section_id], ['level', '=', '4']])->orderBy('id', 'desc')->take(1)->get()->toArray();
                if (count($fetch_user_marks_level4_details) > 0) {
                    $fetch_correct_ans_level4 = $fetch_user_marks_level4_details[0]['total_correct_ans'];
                } else {
                    $fetch_correct_ans_level4 = 0;
                }
                if ($fetch_correct_ans_level1 >= 3 && $fetch_correct_ans_level2 >= 3 && $fetch_correct_ans_level3 >= 3 && $fetch_correct_ans_level4 >= 3) {
                    array_push($area_section_lock_entry, 0);
                } else {
                    array_push($area_section_lock_entry, 1);
                }
                $fetch_study_mat_log = StudentStudyMatLog::where([['student_id', '=', $user_id], ['exam_id', '=', $exam_id], ['subject_id', '=', $subject_id], ['area_id', '=', $area_id], ['section_id', '=', $section_id]])->orderBy('id', 'desc')->take(1)->get()->toArray();
                if ($area_lock == '1') {
                    $section_test_lock = '1';
                } else if (count($fetch_study_mat_log) > 0) {
                    $video_log = $fetch_study_mat_log[0]['video'];
                    $pdf_log = $fetch_study_mat_log[0]['pdf'];
                    $document_log = $fetch_study_mat_log[0]['document'];
                    $example_log = $fetch_study_mat_log[0]['example'];
                    if ($video_log == 1 && $pdf_log == 1 && $document_log == 1 && $example_log == 1) {
                        $section_test_lock = '0';
                    } else {
                        $section_test_lock = '1';
                    }
                } else {
                    $section_test_lock = '1';
                }
                $section_details[$sec_key]['section_lock'] = $section_lock;
                $section_details[$sec_key]['section_test_lock'] = $section_test_lock;
                $i++;
                $pre_area_id = $area_id;
            }
            if ($user['subscription'] == 0) {
                $area_test_lock = '1';
            } else {
                foreach ($area_section_lock_entry as $lock_key => $lock_value) {
                    if ($lock_value == 1) {
                        $area_test_lock = '1';
                        break;
                    } else {
                        $area_test_lock = '0';
                    }
                }
            }
            array_push($area_test_lock_entry, $area_test_lock);
            $area_details[$key]['area_test_lock'] = $area_test_lock;
            $area_details[$key]['sections'] = $section_details;
        }

        foreach ($area_test_lock_entry as $area_lock_key => $area_lock_value) {
            if ($area_lock_value == 1) {
                $subject_test_lock = '1';
                break;
            } else {
                $subject_test_lock = '0';
            }
        }

        if ($area_details) {
            return response()->json(['msg' => 'Success', 'status_code' => 200, 'data' => $area_details, 'subject_test_lock' => $subject_test_lock]);
        } else {
            return response()->json(['msg' => 'No area available', 'status_code' => 404]);
        }
    }

    public function get_section(Request $request) {
        $area_id = $request->area_id;
        $section = Section::where('area_id', $area_id)->get()->toArray();
        if ($section) {
            return response()->json(['msg' => 'Success', 'status_code' => 200, 'data' => $section]);
        } else {
            return response()->json(['msg' => 'No data available', 'status_code' => 404]);
        }
    }

    public function get_studymat(Request $request) {
        $studymat_arr = array();
        $exam_id = $request->exam_id;
        $subject_id = $request->subject_id;
        $area_id = $request->area_id;
        $section_id = $request->section_id;
        $studymat_details = StudyMat::where([['subject_id', '=', $subject_id], ['exam_id', 'LIKE', "%".$exam_id."%"], ['area_id', '=', $area_id], ['section_id', '=', $section_id]])->get()->toArray();
        if ($studymat_details) {
            foreach ($studymat_details as $key => $value) {
                $fetch_subject = Subject::where('id', $value['subject_id'])->get()->toArray();
                $fetch_area = Area::where('id', $value['area_id'])->get()->toArray();
                $fetch_section = Section::where('id', $value['section_id'])->get()->toArray();

                /*$video = array();
                $video_lists = unserialize($value['video']);
                foreach ($video_lists as $v) {
                    $video[] = url('/') .'/upload/study_video/'. $v['video'];
                }*/

                $fetch_studymat_videos = StudyMatVideo::where('study_mat_id', $value['id'])->orderBy('video_order', 'asc')->get()->toArray();
                foreach ($fetch_studymat_videos as $video_key => $video_value) {
                    $video_url = url('/') .'/upload/study_video/'. $video_value['video_file'];
                    $fetch_studymat_videos[$video_key]['video_url'] = $video_url;
                }

                $pdf = array();
                $pdf_lists = unserialize($value['pdf']);
                foreach ($pdf_lists as $p) {
                    $file_name = url('/') .'/upload/study_pdf/'. $p['pdf'];
                    $file_extn = pathinfo($p['pdf'], PATHINFO_EXTENSION);
                    $pdf[] = array(
                        'file' => $file_name,
                        'ext' => $file_extn
                    );
                }

                /*$doc = array();
                $doc_lists = unserialize($value['document']);
                foreach ($doc_lists as $d) {
                    $doc[] = url('/') .'/upload/study_doc/'. $d['doc'];
                }*/

                $fetch_studymat_theories = StudyMatTheory::where('study_mat_id', $value['id'])->orderBy('theory_order', 'asc')->get()->toArray();
                foreach ($fetch_studymat_theories as $thry_key => $thry_value) {
                    $theory_url = url('/') .'/upload/study_doc/'. $thry_value['theory_file'];
                    $fetch_studymat_theories[$thry_key]['theory_url'] = $theory_url;
                }

                $fetch_studymat_sample_ques = StudyMatSampleQues::where('study_mat_id', $value['id'])->orderBy('ques_order', 'asc')->get()->toArray();

                $studymat_arr[] = array(
                    'subject_name' => $fetch_subject[0]['sub_full_name'],
                    'area_name' => $fetch_area[0]['name'],
                    'section_name' => $fetch_section[0]['name'],
                    'video' => $fetch_studymat_videos,
                    'course_structure' => $pdf,
                    'theory' => $fetch_studymat_theories,
                    'description' => $value['description'],
                    'duration' => $value['duration'] . " hrs",
                    'sample_ques_ans' => $fetch_studymat_sample_ques
                );
            }
            return response()->json(['msg' => 'Success', 'status_code' => 200, 'data' => $studymat_arr]);
        } else {
            return response()->json(['msg' => 'No study material available', 'status_code' => 404]);
        }
    }

    public function add_user_exam(Request $request) {
        $ans_arr = array();
        
        $user = JWTAuth::toUser($request->token);
        $user_id = $user['id'];

        $exam_id = ($request->exam_id != '') ? $request->exam_id : 0;
        $subject_id = ($request->subject_id != '') ? $request->subject_id : 0;
        $area_id = ($request->area_id != '') ? $request->area_id : 0;
        $section_id = ($request->section_id != '') ? $request->section_id : 0;
        $level = ($request->level != '') ? $request->level : 0;
        $question_id = ($request->question_id != '') ? $request->question_id : 0;

        $user_exam = new UserExam();
        $user_exam->student_id = $user_id;
        $user_exam->exam_id = $exam_id;
        $user_exam->subject_id = $subject_id;
        $user_exam->area_id = $area_id;
        $user_exam->section_id = $section_id;
        $user_exam->level = $level;
        $user_exam->question_id = $question_id;

        $fetch_question_type = QuestionAnswer::where('id', $question_id)->select('option_type')->get()->toArray();
        if ($fetch_question_type[0]['option_type'] == 'mcq') {
            $ans_arr = explode(',', $request->user_answer);
            $user_answer = serialize($ans_arr);

            $user_exam->user_answer = $user_answer;
        }
        if ($fetch_question_type[0]['option_type'] == 'numeric') {
            $user_exam->numeric_ans = $request->user_answer;
        }

        if ($user_exam->save()) {
            return response()->json(['msg' => 'Success', 'status_code' => 200]);
        } else {
            return response()->json(['msg' => 'Error.', 'status_code' => 500]);
        }
    }

    public function update_study_mat_log(Request $request) {
        $user = JWTAuth::toUser($request->token);
        $user_id = $user['id'];

        $exam_id = $request->exam_id;
        $subject_id = $request->subject_id;
        $area_id = $request->area_id;
        $section_id = $request->section_id;

        $fetch_user_details = StudentStudyMatLog::where([['student_id', '=', $user_id], ['exam_id', '=', $exam_id], ['subject_id', '=', $subject_id], ['area_id', '=', $area_id], ['section_id', '=', $section_id]])->orderBy('id', 'desc')->take(1)->get()->toArray();
        if (count($fetch_user_details) > 0) {
            $log_id = $fetch_user_details[0]['id'];
            $edit = StudentStudyMatLog::find($log_id);
            $edit->student_id = $user_id;
            $edit->exam_id = $exam_id;
            $edit->subject_id = $subject_id;
            $edit->area_id = $area_id;
            $edit->section_id = $section_id;
            $edit->video = ($request->video != '') ? $request->video : $edit->video;
            $edit->document = ($request->document != '') ? $request->document : $edit->document;
            $edit->pdf = ($request->pdf != '') ? $request->pdf : $edit->pdf;
            $edit->example = ($request->example != '') ? $request->example : $edit->example;
            if ($edit->save()) {
                return response()->json(['status_code' => 200, 'msg' => 'Success']);
            } else {
                return response()->json(['status_code' => 500, 'msg' => 'Error']);
            }
        } else {
            $add = new StudentStudyMatLog();
            $add->student_id = $user_id;
            $add->exam_id = $exam_id;
            $add->subject_id = $subject_id;
            $add->area_id = $area_id;
            $add->section_id = $section_id;
            $add->video = ($request->video != '') ? $request->video : 0;
            $add->document = ($request->document != '') ? $request->document : 0;
            $add->pdf = ($request->pdf != '') ? $request->pdf : 0;
            $add->example = ($request->example != '') ? $request->example : 0;
            if ($add->save()) {
                return response()->json(['msg' => 'Success', 'status_code' => 200]);
            } else {
                return response()->json(['msg' => 'Error.', 'status_code' => 500]);
            }
        }
    }

    public function get_tip_of_the_day(Request $request) {
        $cur_date = Carbon::now()->format('Y-m-d');
        
        $today_tips = Tips::where('created_at', 'like', '%' . $cur_date . '%')->orderBy('created_at', 'desc')->get()->toArray();
        if ($today_tips) {
            return response()->json(['msg' => 'Success', 'status_code' => 200, 'data' => $today_tips]);
        } else {
            return response()->json(['msg' => 'No tips available for today', 'status_code' => 404]);
        }
    }

    public function add_user_exam_answer(Request $request) {
        $ans_arr = array();
        
        $user = JWTAuth::toUser($request->token);
        $user_id = $user['id'];

        $exam_id = ($request->exam_id != '') ? $request->exam_id : 0;
        $question_id = ($request->question_id != '') ? $request->question_id : 0;

        $user_exam = new UserExamAnswer();
        $user_exam->student_id = $user_id;
        $user_exam->exam_id = $exam_id;
        $user_exam->question_id = $question_id;

        $fetch_question_type = ExamQuestionAnswer::where('id', $question_id)->select('option_type')->get()->toArray();
        if ($fetch_question_type[0]['option_type'] == 'mcq') {
            $ans_arr = explode(',', $request->user_answer);
            $user_answer = serialize($ans_arr);

            $user_exam->user_answer = $user_answer;
        }
        if ($fetch_question_type[0]['option_type'] == 'numeric') {
            $user_exam->numeric_ans = $request->user_answer;
        }

        if ($user_exam->save()) {
            return response()->json(['msg' => 'Success', 'status_code' => 200]);
        } else {
            return response()->json(['msg' => 'Error.', 'status_code' => 500]);
        }
    }

    public function test_noti(Request $request) {
        

        $notificationBuilder = new PayloadNotificationBuilder('my title');
        $notificationBuilder->setBody('Hello world')
                            ->setSound('default');

        $notification = $notificationBuilder->build();

        $token = "e1qY7inm-D4:APA91bGvohOlwceEBZN3C3J81IQhYMLFHrMv3or2-XfPFBK5ErhTCRajF7i9FWTNTJofZ-zdVAQeDQ3axswJDF2ji0E2-71GMMhzbjlAJrjcfJ5mBLjNzrboieUpLlSq4NJLZup4gYyE";

        $downstreamResponse = FCM::sendTo($token, null, $notification, null);

        

        return response()->json(['success' => $downstreamResponse->numberSuccess(), 'fail' => $downstreamResponse->numberFailure(), 'modify' => $downstreamResponse->numberModification()]);
    }
}

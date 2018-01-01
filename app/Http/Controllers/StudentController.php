<?php
namespace App\Http\Controllers;

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');

use Illuminate\Http\Request;
use App\Student;
use App\Exam;
use App\Area;
use App\Subject;
use App\Section;
use App\StudyMat;
use App\StudyMatSampleQues;
use App\UserExam;
use JWTAuth;
use JWTAuthException;
use Validator;
use Config;
use Illuminate\Support\Facades\Mail;
use App\Mail\Registration;
use App\QuestionAnswer;
use App\SubjectExam;

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
            $image = url('/') .'/upload/profile_image/resize/'. $user->image;
        }
        if ($user->status == 0) {
            return response()->json(['msg' => 'Account not activated.', 'status_code' => 404]);
        } else {
            return response()->json([
                'msg' => 'Successfully login',
                'status_code' => 200,
                'token' => $token,
                'first_name' => ucfirst($user->first_name),
                'last_name' => ucfirst($user->last_name),
                'image' => $image,
                'exam_id' => $user->exam_id
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
        $exam_id = $request->exam_id;
        $subject_id = $request->subject_id;
        $area_details = Area::where([['exam_id', '=', $exam_id],['subject_id', '=', $subject_id]])->get()->toArray();

        foreach ($area_details as $key => $value) {
            $description = strip_tags($value['description']);
            $area_id = $value['id'];
            $area_details[$key]['description'] = $description;
            $fetch_sections = Section::where('area_id', $area_id)->get()->toArray();
            $area_details[$key]['sections'] = $fetch_sections;
            
        }

        if ($area_details) {
            return response()->json(['msg' => 'Success', 'status_code' => 200, 'data' => $area_details]);
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
        $studymat_details = StudyMat::where([['subject_id', '=', $subject_id],['exam_id', 'LIKE', "%".$exam_id."%"],['area_id', '=', $area_id],['section_id', '=', $section_id]])->get()->toArray();
        if ($studymat_details) {
            foreach ($studymat_details as $key => $value) {
                $fetch_subject = Subject::where('id', $value['subject_id'])->get()->toArray();
                $fetch_area = Area::where('id', $value['area_id'])->get()->toArray();
                $fetch_section = Section::where('id', $value['section_id'])->get()->toArray();

                $video = array();
                $video_lists = unserialize($value['video']);
                foreach ($video_lists as $v) {
                    $video[] = url('/') .'/upload/study_video/'. $v['video'];
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

                $doc = array();
                $doc_lists = unserialize($value['document']);
                foreach ($doc_lists as $d) {
                    $doc[] = url('/') .'/upload/study_doc/'. $d['doc'];
                }

                $fetch_studymat_sample_ques = StudyMatSampleQues::where('study_mat_id', $value['id'])->get()->toArray();

                $studymat_arr[] = array(
                    'subject_name' => $fetch_subject[0]['sub_full_name'],
                    'area_name' => $fetch_area[0]['name'],
                    'section_name' => $fetch_section[0]['name'],
                    'video' => $video,
                    'course_structure' => $pdf,
                    'theory' => $doc,
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

        $exam_id = $request->exam_id;
        $subject_id = $request->subject_id;
        $area_id = $request->area_id;
        $section_id = $request->section_id;
        $question_id = $request->question_id;

        $user_exam = new UserExam();
        $user_exam->student_id = $user_id;
        $user_exam->exam_id = $exam_id;
        $user_exam->subject_id = $subject_id;
        $user_exam->section_id = $section_id;
        $user_exam->area_id = $area_id;
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
            return response()->json(['msg' => 'Successfully inserted', 'status_code' => 200]);
        } else {
            return response()->json(['msg' => 'Insertion error.', 'status_code' => 500]);
        }
    }
}

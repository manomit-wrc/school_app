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
use App\UserExam;
use JWTAuth;
use JWTAuthException;
use Validator;
use Config;

class StudentController extends Controller
{
    public function registration(Request $request) {
    	$validator = Validator::make($request->all(),[
    		'username' => 'required|unique:students,username',
    		'email' => 'required|email|unique:students,email',
    		'password' => 'required',
    		'mobile_no' => 'required|max:10|min:10|regex:/[0-9]{10}/'
		],[
			'username.required' => 'Please enter username',
			'email.required' => 'Please enter email id'
		]);
		if ($validator->fails()) {
            return response()->json(['error' => true,
                'message' => $validator->messages()->first(),
                'status_code' => 500]);
        }
        else {
        	$student = new Student();
        	$student->username = $request->username;
        	$student->email = $request->email;
        	$student->password = bcrypt($request->password);
        	$student->mobile_no = $request->mobile_no;

        	if ($student->save()) {
        		return response()->json(['error' => false,
                'message' => 'Registration has been successfully completed',
                'status_code' => 200]);
        	}
        }
    }

    public function login(Request $request) {
        Config::set('tymon.jwt.provider.jwt', '\App\Student');
        $credentials = $request->only('username', 'password');
        $token = null;
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['msg' => 'Invalid Username or password', 'status_code' => 404]);
            }
        } catch (JWTAuthException $e) {
            return response()->json(['msg' => 'Failed to create token', 'status_code' => 500]);
        }
        $user = JWTAuth::toUser($token);
        if ($user->status == 0) {
            return response()->json(['msg' => 'Account not activated.', 'status_code' => 404]);
        } else {
            return response()->json(['msg' => 'Successfully login', 'status_code' => 200, 'token' => $token]);
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
        $subject = Subject::where('exam_id', $exam_id)->get()->toArray();
        if ($subject) {
            return response()->json(['msg' => 'Success', 'status_code' => 200, 'data' => $subject]);
        } else {
            return response()->json(['msg' => 'No subject available', 'status_code' => 404]);
        }
    }

    public function get_area(Request $request) {
        $exam_id = $request->exam_id;
        $subject_id = $request->subject_id;
        $area_details = Area::where([['exam_id', '=', $exam_id],['subject_id', '=', $subject_id]])->get()->toArray();
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
        $subject_id = $request->subject_id;
        $area_id = $request->area_id;
        $section_id = $request->section_id;
        $studymat_details = StudyMat::where([['subject_id', '=', $subject_id],['area_id', '=', $area_id],['section_id', '=', $section_id]])->get()->toArray();
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
                    $pdf[] = url('/') .'/upload/study_pdf/'. $p['pdf'];
                }

                $doc = array();
                $doc_lists = unserialize($value['document']);
                foreach ($doc_lists as $d) {
                    $doc[] = url('/') .'/upload/study_doc/'. $d['doc'];
                }

                $studymat_arr[] = array(
                    'subject_name' => $fetch_subject[0]['sub_full_name'],
                    'area_name' => $fetch_area[0]['name'],
                    'section_name' => $fetch_section[0]['name'],
                    'video' => $video,
                    'pdf' => $pdf,
                    'document' => $doc,
                    'description' => $value['description']
                );
            }
            return response()->json(['msg' => 'Success', 'status_code' => 200, 'data' => $studymat_arr]);
        } else {
            return response()->json(['msg' => 'No study material available', 'status_code' => 404]);
        }
    }

    public function add_user_exam(Request $request) {
        $ans_arr = array();
        $student_id = $request->student_id;
        $exam_id = $request->exam_id;
        $subject_id = $request->subject_id;
        $area_id = $request->area_id;
        $question_id = $request->question_id;
        $ans_arr = explode(',', $request->user_answer);
        $user_answer = serialize($ans_arr);

        $user_exam = new UserExam();
        $user_exam->student_id = $student_id;
        $user_exam->exam_id = $exam_id;
        $user_exam->subject_id = $subject_id;
        $user_exam->area_id = $area_id;
        $user_exam->question_id = $question_id;
        $user_exam->user_answer = $user_answer;

        if ($user_exam->save()) {
            return response()->json(['msg' => 'Successfully inserted', 'status_code' => 200]);
        } else {
            return response()->json(['msg' => 'Insertion error.', 'status_code' => 500]);
        }
    }
}

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
}

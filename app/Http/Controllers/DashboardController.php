<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\User;
use Validator;
use Auth;
use Image;
use App\Student;
use Carbon;

class DashboardController extends Controller
{
 	public function index(){
        $fetch_all_studen_count = count(Student::where('status','1')->get()->toArray());
 		return view('frontend.dashboard')->with('fetch_all_studen_count',$fetch_all_studen_count);
 	}

 	public function view_profile(){
 		return view('frontend.profile.profile');
 	}

 	public function profile_submit(Request $request){
 		$f_name = $request->f_name;
 		$l_name = $request->l_name;
 		$mobile = $request->mobile;
 		$address = $request->address;

 		Validator::make($request->all(),[
 			'f_name' => 'required',
            'l_name' => 'required',
            'mobile' => 'required|numeric|min:10|max:10',
            'address' => 'required'
 		],[
 			'f_name.required' => "<font color='red'>Please enter first name.</font>",
            'l_name.required' => "<font color='red'>Please enter last name.</font>",
            'mobile.required' => "<font color='red'>Please enter valid mobile number.</font>",
            'mobile.numeric' => "<font color='red'>Please enter valid mobile number.</font>",
            'mobile.min' => "<font color='red'>Please enter valid 10 digit mobile number.</font>",
            'mobile.max' => "<font color='red'>Please enter valid 10 digit mobile number.</font>",
            'address.required' => "<font color='red'>Please enter valid address.</font>"
 		])->validate();

 		if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
            $fileName = time().'_'.$file->getClientOriginalName();
            //thumb destination path
            $destinationPath_2 = public_path().'/upload/profile_image/resize/';
            $img = Image::make($file->getRealPath());
            $img->resize(175, 175, function ($constraint) {
              $constraint->aspectRatio();
            })->save($destinationPath_2.'/'.$fileName);
            //original destination path
            $destinationPath = public_path().'/upload/profile_image/original/';
            $file->move($destinationPath,$fileName);
        }
        else {
            $fileName = $request->exiting_profile_image;
        }

        $edit = User::find(Auth::guard('admin')->user()->id);
        $edit->first_name = $f_name;
        $edit->last_name = $l_name;
        $edit->mobile = $mobile;
        $edit->address = $address;
        $edit->image = $fileName;

        if($edit->save()){
        	$request->session()->flash("profile-update", "Profile updated successfully.");
            return redirect('/profile-view');
        }
 	}

 	public function change_password_view(){
 		return view('frontend.profile.change_password');
 	}

    public function logout(Request $request) {
        Auth::guard('admin')->logout();
        return redirect('/');
    }

    public function change_password_submit(Request $request) {
        
        Validator::make($request->all(),[
            'old_password' => 'required|password_exists:' . Auth::guard('admin')->user()->password,
            'new_password' => 'required|different:old_password|min:6|max:32',
            'confirm_password' => 'required|same:new_password'
        ],[
            'old_password.required' => "Please enter old password",
            'old_password.password_exists' => "Old password doesn't matched",
            'new_password.required' => "Please enter new password",
            'new_password.different' => "New password can't be same with old password",
            'new_password.min' => 'New password must have minimum 6 characters',
            'new_password.max' => 'New password must have maximum 32 characters',
            'confirm_password.required' => "Please enter confirm password",
            'confirm_password.same' => "Confirm password must be same with new password"
        ])->validate();

        $edit = User::find(Auth::guard('admin')->user()->id);
        $edit->password = bcrypt($request->new_password);
        if($edit->save()) {
            $request->session()->flash("message", "Password change successfully");
            return redirect('/change-password');
        }

    }

    public function students_list (Request $request) {
        $fetch_all_student = Student::where('status','1')->orderby('id','desc')->get()->toArray();
        return view('frontend.student.list')->with('fetch_all_student',$fetch_all_student);
    }

    public function students_delete (Request $request,$student_id) {
        $delete = Student::find($student_id);
        $delete->status = 0;

        if($delete->save()){
            $request->session()->flash("submit-status", "Student delete successfully");
            return redirect('/students-details');
        }
    }
}

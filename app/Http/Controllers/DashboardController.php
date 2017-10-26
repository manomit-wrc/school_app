<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\User;
use Validator;
use Auth;
use Image;

class DashboardController extends Controller
{
 	public function index(){
 		return view('frontend.dashboard');
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

        $edit = User::find('1');
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
}

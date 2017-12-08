<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Student;
use JWTAuth;
use JWTAuthException;
use Image;

class ProfileController extends Controller
{
    public function index (Request $request) {
    	$user = JWTAuth::toUser($request->token);
    	if(!empty($user['image'])){
    		$profile_image_link = '/upload/app/profile_image/resize/'.$user['image'];
    	}else{
    		$profile_image_link = '/upload/app/avatar.png';
    	}
    	return response()->json(['user'=>$user, 'profile_image_link'=>$profile_image_link]);
    }

    public function profile_edit (Request $request) {
    	$user = JWTAuth::toUser($request->token);
    	$user_id = $user['id'];

    	// if (!empty($request->profile_image)) {
     //        $file = $request->file('profile_image');
     //    	$fileName1 = time().'_'.$file->getClientOriginalName();
        
     //        //thumb destination path
     //        $destinationPath_2 = public_path().'/upload/app/profile_image/resize/';
     //        $img = Image::make($file->getRealPath());
     //        $img->resize(175, 175, function ($constraint) {
     //          $constraint->aspectRatio();
     //        })->save($destinationPath_2.'/'.$fileName1);
     //        //original destination path
     //        $destinationPath = public_path().'/upload/app/profile_image/original/';
     //        $file->move($destinationPath,$fileName1);
     //    }else{
     //    	$fileName1 = $request->exit_profile_image;
     //    }

    	// $edit = Student::find($user_id);
    	// $edit->first_name = $request->first_name;
    	// $edit->last_name = $request->last_name;
    	// $edit->mobile_no = $request->mobile_no;
    	// $edit->address = $request->address;
    	// $edit->city = $request->city;
    	// $edit->pincode = $request->pincode;
    	// $edit->image = $fileName1;

    	// if($edit->save()){
    	// 	return response()->json(['code'=>'100','msg'=>'profile edit successfully.']);
    	// }else{
    	// 	return response()->json(['code'=>'500','msg'=>'profile edit failed.']);
    	// }

    	$data = str_replace('data:image/jpeg;base64,', '', $request->profile_image);
		$data = str_replace(' ', '+', $data);
		$data = base64_decode($data);
		$file = uniqid() . '.jpeg';
		$path = public_path() . "/upload/app/profile_image/resize/" . $file;

		file_put_contents($path, $data);

		return response()->json(['code'=>'500','msg'=>'image upload.']);
    }
}

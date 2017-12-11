<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Student;
use JWTAuth;
use JWTAuthException;
use Image;
use App\QuestionAnswer;

class ProfileController extends Controller
{
    public function index (Request $request) {
    	$user = JWTAuth::toUser($request->token);
    	if(!empty($user['image'])){
    		$profile_image_link = public_path() .'/upload/app/profile_image/resize/'.$user['image'];
    	}else{
    		$profile_image_link = public_path() .'/upload/app/avatar.png';
    	}
    	return response()->json(['user'=>$user, 'profile_image_link'=>$profile_image_link]);
    }

    public function profile_edit (Request $request) {
    	$user = JWTAuth::toUser($request->token);
    	$user_id = $user['id'];

    	if (!empty($request->profile_image)) {
            $encoded_string = $request->profile_image;
			$imgdata = base64_decode($encoded_string);
			$f = finfo_open();
			$mime_type = finfo_buffer($f, $imgdata, FILEINFO_MIME_TYPE);
			$image_ext = substr($mime_type, 6);

	    	$data = str_replace('data:'.$mime_type.';base64,', '', $encoded_string);
			$data = str_replace(' ', '+', $data);
			$data = base64_decode($data);
			$file = time() . '_profile_image.'.$image_ext;
			$path = public_path() . "/upload/app/profile_image/original/" . $file;

			file_put_contents($path, $data);

			$encoded_string = $request->profile_image;
			$imgdata = base64_decode($encoded_string);

	    	$info    = getimagesizefromstring($imgdata);
	        $old_width = $info[0];
	        $old_height = $info[1];

	        $WIDTH                  = 100; // The size of your new image
			$HEIGHT                 = 100; 

	        //new resource
	        $resource = imagecreatefromstring($imgdata);

	        $resource_copy  = imagecreatetruecolor($WIDTH, $HEIGHT);

	        imagealphablending( $resource_copy , false );
	        imagesavealpha( $resource_copy , true );

	        imagecopyresampled($resource_copy, $resource, 0, 0, 0, 0, $WIDTH, $HEIGHT, $old_width, $old_height);

	        $url = public_path() . "/upload/app/profile_image/resize/".$file;
	        $final = imagepng($resource_copy, $url, 9);

        }else{
        	$file = $request->exit_profile_image;
        }

    	$edit = Student::find($user_id);
    	$edit->first_name = $request->first_name;
    	$edit->last_name = $request->last_name;
    	$edit->mobile_no = $request->mobile_no;
    	$edit->address = $request->address;
    	$edit->city = $request->city;
    	$edit->pincode = $request->pincode;
    	$edit->image = $file;

    	if($edit->save()){
    		return response()->json(['code'=>'100','msg'=>'profile edit successfully.']);
    	}else{
    		return response()->json(['code'=>'500','msg'=>'profile edit failed.']);
    	}
    }

    public function fetch_question (Request $request) {
    	$fetch_question_details = QuestionAnswer::where('status','1')->orderby('id','desc')->get()->toArray();

    	return response()->json(['code'=>'100','fetch_question_details'=>$fetch_question_details]);
    }
}

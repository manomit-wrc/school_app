<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Auth;
use Image;
use App\Banner;

class BannerController extends Controller
{
    public function index (Request $request) {
    	$all_banner_details = Banner::where('status','1')->orderby('id','desc')->get()->toArray();
    	return view ('frontend.banner.listings')->with('all_banner_details',$all_banner_details);
    }

    public function add (Request $request) {
    	return view ('frontend.banner.add');
    }

    public function add_submit (Request $request) {
    	Validator::make($request->all(),[
    		'banner_name' => 'required|unique:banners,banner_name',
    		'banner_image' => 'required|mimetypes:image/jpeg,image/png,image/jpg|max:6144',
    	],[
    		'banner_name.required' => 'Please enter banner name.',
    		'banner_name.unique' => 'Banner name already exit.',
    		'banner_image.required' => 'Please enter banner image.',
    		'banner_image.*.mimetypes' => 'Please upload correct file.',
    		'banner_image.*.max' => 'Please upload file within 6MB'
    	])->validate();

    	if ($request->hasFile('banner_image')) {
            $file = $request->file('banner_image');
        	$fileName1 = time().'_'.$file->getClientOriginalName();
        
            //thumb destination path
            $destinationPath_2 = public_path().'/upload/banner_file/resize';
            $img = Image::make($file->getRealPath());
            $img->resize(360, 640, function ($constraint) {
				$constraint->aspectRatio();
            })->save($destinationPath_2.'/'.$fileName1);
            //original destination path
            $destinationPath = public_path().'/upload/banner_file/original/';
            $file->move($destinationPath,$fileName1);
        }

        $add = new Banner();
        $add->banner_name = $request->banner_name;
        $add->banner_image = $fileName1;
        $add->status = 1;

        if($add->save()){
        	$request->session()->flash("submit-status", "Banner added successfully.");
            return redirect('/banner');
        }
    }
}

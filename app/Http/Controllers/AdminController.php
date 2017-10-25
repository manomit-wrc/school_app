<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\User;
use Validator;
use Auth;

class AdminController extends Controller
{
    public function index(Request $request){
    	return view('login_layout');
    }

    public function login_submit(Request $request) {
        Validator::make($request->all(),[
            'user_email' => 'required',
            'password' => 'required'
        ],[
            'user_email.required' => 'Please enter your email address',
            'password.required' => 'Please enter your password'
        ])->validate();

        if (Auth::guard('admin')->attempt(['email' => $request->user_email, 'password' => $request->password])){
            return redirect('/dashboard');
        }else{
            $request->session()->flash("login-failed", "Email or Password is wrong.");
            return redirect('/');
        }
    }
}

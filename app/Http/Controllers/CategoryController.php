<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

class CategoryController extends Controller
{
    public function index() {
    	$categories = \App\Category::all();
    	return view('frontend.category.index')->with('categories', $categories);
    }

    public function add() {
    	$categories = \App\Category::pluck('name', 'id');
    	return view('frontend.category.add')->with('categories', $categories);
    }

    public function save(Request $request) {
    	Validator::make($request->all(),[
    		'p_id' => 'required',
    		'name' => 'required',
    		'description' => 'required'
		],[
			'p_id.required' => 'Please select parent category',
			'name.required' => 'Please enter category name',
			'description.required' => 'Please enter description'
		])->validate();

		$category = new \App\Category();
		$category->p_id = $request->p_id;
		$category->name = $request->name;
		$category->description = $request->description;

		if($category->save()) {
			$request->session()->flash("submit-status", "Category added successfully.");
            return redirect('/category');
		}
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

class CategoryController extends Controller
{
    public function index() {
    	$categories = \App\Category::with('parent')->get()->toArray();
        
    	return view('frontend.category.index')->with('categories', $categories);
    }

    public function add() {
    	
        $category = new \App\Category();
        $categories = $category->tree();
        $category_array = array();
        foreach ($categories->toArray() as  $value) {
           $name = $category->generate_dropdown($value, $value['name']);
           $category_array[] = array('id'=>$value['id'], 'name' => $name);
        }

    	return view('frontend.category.add')->with('categories', $category_array);
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

    public function edit($id) {
        $category = new \App\Category();
        $categories = $category->tree();
        $category_array = array();
        foreach ($categories->toArray() as  $value) {
           $name = $category->generate_dropdown($value, $value['name']);
           $category_array[] = array('id'=>$value['id'], 'name' => $name);
        }

        return view('frontend.category.edit')->with('categories', $category_array)->with('category', \App\Category::find($id));
    }

    public function update(Request $request, $id) {
        Validator::make($request->all(),[
            'p_id' => 'required',
            'name' => 'required',
            'description' => 'required'
        ],[
            'p_id.required' => 'Please select parent category',
            'name.required' => 'Please enter category name',
            'description.required' => 'Please enter description'
        ])->validate();

        $category = \App\Category::find($id);

        $category->p_id = $request->p_id;
        $category->name = $request->name;
        $category->description = $request->description;

        if($category->save()) {
            $request->session()->flash("submit-status", "Category updated successfully.");
            return redirect('/category');
        }
    }

    public function delete(Request $request, $id) {
        $category = \App\Category::find($id);
        if($category->delete()) {
            $request->session()->flash("submit-status", "Category deleted successfully.");
            return redirect('/category');
        }
    }
}

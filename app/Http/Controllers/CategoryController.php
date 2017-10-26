<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index() {
    	return view('frontend.category.index');
    }

    public function add() {
    	return view('frontend.category.add');
    }
}

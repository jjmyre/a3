<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WelcomeController extends Controller
{
	function __invoke(Request $request){
		return view('form');
	}
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TaxResultsController extends Controller
{
	function __invoke(){
		return view('results');
	}
}
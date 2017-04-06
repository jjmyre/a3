<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TaxYearController extends Controller
{
	function __invoke(Request $request){

		// Get year from select box

		$year = $request->input('year');
		
		// Validate Select box 

		$this->validate($request, [
            'year' => 'required',
        	]);

        // Return view with year

		return view('form')->with([
			'year' => $year,
			]);
	}
}

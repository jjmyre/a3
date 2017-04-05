<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TaxYearController extends Controller
{
	function __invoke(Request $request){

		$year = $request->input('year');
		
		// Validate Select 

		$this->validate($request, [
            'year' => 'required',
        ]);

        // Return view with year

		return view('form')->with([
			'year' => $year,
		]);
	}
}

<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

if(config('app.env') == 'local') {
    Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
}

Route::get('/', 'WelcomeController');

Route::get('/taxyear','TaxYearController');

Route::get('/taxyear2016/estimate', 'TaxEstimateController@estimate2016');

Route::get('/taxyear2017/estimate', 'TaxEstimateController@estimate2017');







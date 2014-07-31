<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

//Since angular.js uses {{ }} syntax, set Blade to use different syntax.

Blade::setContentTags('<%', '%>'); 		// for variables and all things Blade
Blade::setEscapedContentTags('<%%', '%%>'); 	// for escaped data

// Route::get('/', function()
// {
	// return View::make('hello');
// });

// Route::get('sms', function() {
	// return View::make('sms');
// });

Route::get('sms-tool', function() {
	return View::make('sms-tool');
});

Route::post('upd', 'HomeController@updateFile');



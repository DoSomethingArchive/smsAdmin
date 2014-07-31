<?php

class HomeController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	// public function showWelcome()
	// {
		// return View::make('hello');
	// }
	
	public function updateFile() {
		
		 date_default_timezone_set("America/New_York");
		 
		 $destination = 'ds/lib/ds/';
		 
		 $filedestination = $destination . Input::get('destination');
		 $logdestination = $destination . "updates.log";
		 $log = Input::get('log');
		 $file = Input::get('file');
		 
		 $log = "\nOn " . date("F d, Y, g:i A") . "\n" . $log;
		 
		 
		 file_put_contents($filedestination, json_encode ($file, JSON_NUMERIC_CHECK));
		 file_put_contents($logdestination, $log, FILE_APPEND);
		 
		 return $destination;
		 
		 
		 
	}

}

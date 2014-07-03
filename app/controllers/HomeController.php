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

	public function showWelcome()
	{
		return View::make('hello');
	}
	
	public function updateFile() {
		 // return dd(Input::get());
		 
		 
		 $destination = 'ds/lib/ds/';
		 $destination .= 'copy' . Input::get('destination');
		 
		 $file = Input::get('file');
		 json_decode($file);
		 return "Here";
		 $file = json_decode($file);
		 return "Here";
		 // return $file;
		 // file_put_contents($destination, print_r($file, TRUE));
		 
		 file_put_contents($destination, json_encode ($file));
		 // return "Here";
		 return $destination;
		 
		 
		 // return View::make('hello');
	}

}

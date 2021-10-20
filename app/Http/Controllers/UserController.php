<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use DB;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Event;
use App\Events\SendLinkEmailEvent;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

	const PER_PAGE = 10;
    private $_repository;

    public function __construct() {
    	//$this->_repository = $user;
    }

    public function login(Request $request)
    {
        return view('login');
    }

    public function dologin(Request $request)
    {
        $input = $request->only('email', 'password');

        $rules = [
            'email' =>  'required|email|exists:users,email',
            'password' =>  'required|alphaNum|min:3'
        ];

        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        } else {
        	//dd(Auth::attempt($input));
        	if( Auth::attempt($input) ){

        		return redirect('/');

        	} else {

        		return redirect('login')->with('error', 'Email and password donot match.');
        	}
        }
    }

    public function doLogout()
	{
	    Auth::logout(); // log the user out of our application
	    return Redirect::to('login'); // redirect the user to the login screen
	}
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use DB;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Event;
use App\Events\SendLinkEmailEvent;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;

class SettingsController extends Controller
{

	const PER_PAGE = 10;
    private $_repository;

    public function __construct() {
    	//$this->_repository = $user;
    }

    public function showSettings()
    {
    	$settings = Setting::where('type', 'allowed_files')->first();
    	return view('settings', [ 'allowed' => $settings ]);
    }

    public function storeOrUpdate(Request $request)
    {
        $input = $request->only('text', 'type');

        $rules = [
            'text' =>  'required',
            'type' => 'required',
        ];

        $msgs = [
        	
        	'text.required' => "This field is required."
        ];

        $validator = Validator::make($input, $rules,$msgs);

        if ( $validator->fails() ) {
            return Redirect::back()->withErrors($validator);
        } else {

        	$settings = Setting::firstOrNew( array('type' => $input['type'] ));
			$settings->text = $input['text'];

            if( $settings->save() ){
                return Redirect::back()->with('success', 'Settings updated successfully.');
            } else {
                return Redirect::back()->with('error', 'An error occurred while updating settings.');
            }

        }
    }

}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use DB;
use App\Models\Client;
use App\Models\File;
use App\Models\ClientLink;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Event;
use App\Events\SendLinkEmailEvent;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;

class ClientController extends Controller
{

	const PER_PAGE = 10;
    private $_repository;

    public function __construct() {
    	//$this->middleware('auth');
    }

    public function allClients()
    {
    	$clients = Client::orderby('id', 'desc')->paginate(10);
    	//dd($clients);
    	return view('clients', [ 'clients' => $clients] );
    	//return view( 'clients', [ 'cleints' => $clients ]);
    	/*$input = request(['firebase_id']);*/
    }

    public function create(Request $request)
    {
        return view('create-client');
    }

    public function show($id)
    {
        $client = Client::find( $id );
        if( $client ){
            $clientLinks = $client->client_links()->orderby('id', 'desc')->paginate(10);
            $countLinks = $client->client_links()->count();

            return view('show-client', [ 
                                        'client' => $client,
                                        'links'  => $clientLinks
                                    ]);
        } else {

            return redirect()->route('404');

        }
        
    }

    public function edit($id)
    {
        $client = Client::find( $id );

        return view('edit-client', [ 'client' => $client]);
    }

    public function store(Request $request)
    {
        $input = $request->only('name', 'email');
        //dd($input);
        $rules = [
            'name' =>  'required',
            'email' =>  'required|unique:clients'
        ];

        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        } else {

            $client = new Client;

            $client->name = $request->name;
            $client->email = $request->email;

            if( $client->save() ){
                return redirect('/')->with('success', 'Client created successfully.');
            } else {
                return redirect('client/create')->with('error', 'An error occurred while creating client.');
            }

        }
    }

    public function update(Request $request)
    {
        $input = $request->only('id','name', 'email');

        $rules = [
            'id' => 'required|exists:clients,id',
            'name' =>  'required',
            'email' =>  'required|unique:clients,email,'.$input['id']
        ];

        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {

            return Redirect::back()->withErrors($validator);
        
        } else {

            $client = Client::find( $input['id'] );

            $client->name = $request->name;
            $client->email = $request->email;

            if( $client->save() ){
                return redirect( 'client/show/'.$input['id'] )->with('success', 'Client updated successfully.');
            } else {
                return Redirect::back()->with('error', 'An error occurred while updating client.');
            }

        }
    }

    public function generateLink(Request $request){

    	$input = $request->only('id');
    	//dd($input);

        $rules = [
            'id'  =>  'required|exists:clients,id',
        ];

        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            
            $code = 406;
            $output = ['error' => [ 'code' => $code, 'messages' => $validator->messages()->all() ] ];
            
        } else {


            $addLink = new ClientLink;
        	$addLink->link = $this->generateRandomString(5);
            $addLink->link_expire = Carbon::now()->addMinutes(120);
            $addLink->client_id = $input['id'];

        	//$date = $start->addDays($i);
        	/*$update = DB::table('clients')
              ->where('id', $input['id'])
              ->update(
              			[
              				'link' => $this->generateRandomString(5),
              				'link_expire' => Carbon::now()->addMinutes(120)
              			]);


            $client = Client::find($input['id']);*/

            if( $addLink->save() ){

            	$output['data'] = $addLink;
            	$output['code'] = 200;

            } else {

            	$output = ['error' => [ 'code' => 406, 'messages' => "An error occurred." ] ];
            }

        }

        return response()->json($output);

    }

    public  function generateRandomString( $length = 20 ) {
        
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public  function sendEmail(Request $request){

    	$input = $request->only('id', 'link', 'link_id' );
        
        $rules = [
            'id'  =>  'required|exists:clients,id',
            'link' => 'required',
            'link_id' => 'required|exists:client_generated_links,id'
        ];

        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            
            $code = 406;
            $output = ['error' => [ 'code' => $code, 'messages' => $validator->messages()->all() ] ];
            
        } else {

        	$clientLink = ClientLink::find( $input['link_id'] );
            //dd($clientLink->client);
        	Event::dispatch( new SendLinkEmailEvent($clientLink));

            $update = ClientLink::where( 'id', $input['link_id'] )->update(['is_email' => 'yes' ]);

            if($update){
                $output = [ 'code' => 200, 'message' => 'Email has been sent.'];
            } else {
                $output = [ 'code' => 400, 'message' => 'An error occurred.'];
            }
        	
        }

        return response()->json($output);

    }

    public function uploadFiles($link){
        
        $link = ClientLink::where('link', $link )->first();
        $settings = Setting::where('type', 'allowed_files')->first();

        if($link){
            
            $clienFiles = [];
            $client = Client::find( $link->client_id );
            if( $link->link_used == 'yes' ){
                $clienFiles = $client->client_files()->where('link_id', $link->id)->paginate(10);
            }
            if( $link->link_expire->gt( Carbon::now() ) ){
                return view( 'upload-files', [ 'link' => $link, 'client' => $client, 'clienFiles' => $clienFiles, 'allowed' => $settings ] );
            } else {

                return redirect()->route('404');
            }
            
        } else {

            return redirect()->route('404');
        }


    }

    public function uploads(Request $request){
        
        $input = $request->only('file', 'link');

        $client = ClientLink::where('link', $input['link'] )->first();

        if( $client->link_expire->gt( Carbon::now() ) ){
            
            $file = $input['file'];

            $storage = Storage::put('files', $file);

            if($storage){

                $insertFile = new File;
                $insertFile->link_id = $client->id;
                $insertFile->client_id = $client->client_id;
                $insertFile->file_path = $storage;
                $insertFile->file_name = $file->getClientOriginalName();

                if( $insertFile->save() ){

                    $client->link_used = 'yes';
                    //$client->link_expire = $client->link_expire;
                    $client->save();

                    return response()->json(['success' => 'File uploaded successfully.', 'data' => $insertFile ], 200);
                } else {
                    return response()->json(['error' => 'An error occurred while uploading file.'], 400);
                }

            } 

            return response()->json(['error' => 'An error occurred.'], 400 );

        } else {
            return response()->json(['error' => 'An error occurred.'], 400);
        }

    }

    public function setExpiryTime(Request $request)
    {
        $input = $request->only('id', 'time');
        
        $rules = [
            'id' =>  'required|exists:client_generated_links,id',
            'time' =>  'required|integer'
        ];

        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        } else {

            $client = ClientLink::find( $input['id'] );
            $client->link_expire = Carbon::now()->addHours($input['time']);

            if( $client->save() ){
                return Redirect::back()->with('success', 'Time updated successfully.');
            } else {
                return Redirect::back()->with('error', 'An error occurred while updating time.');
            }

        }
    }

    public function deleteClient(Request $request)
    {
        $input = $request->only('id');
        //dd($input);
        $rules = [
            'id' =>  'required|exists:clients,id'
        ];

        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        } else {

            $client = Client::find( $input['id'] );

            if( $client->delete() ){
                return redirect( '/' )->with('success', 'Client deleted successfully.');
            } else {
                return redirect('/')->with('error', 'An error occurred while deleting client.');
            }

        }
    }

    public function deleteLink(Request $request)
    {
        $input = $request->only('id');
        //dd($input);
        $rules = [
            'id' =>  'required|exists:client_generated_links,id'
        ];

        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            return Redirect::back()->with('error', 'An error occurred while deleting Link.');
        } else {

            $client = ClientLink::find( $input['id'] );

            if( $client->delete() ){
                return Redirect::back()->with('success', 'Link deleted successfully.');
            } else {
                return Redirect::back()->with('error', 'An error occurred while deleting Link.');
            }

        }
    }
}
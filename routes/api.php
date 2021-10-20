<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/


/*Route::middleware('auth:api')->post('generate-link', 'App\Http\Controllers\ClientController@generateLink');*/
Route::post('send-email', 'App\Http\Controllers\ClientController@sendEmail');


Route::post('uploads', 'App\Http\Controllers\ClientController@uploads');
Route::post('remove-file', 'App\Http\Controllers\FileController@remove');

Route::post('find_data', 'App\Http\Controllers\FileController@findData');

//Route::post('client/store', 'App\Http\Controllers\ClientController@storeOrUpdate');

//Route::post('client/store-or-update', [ClientController::class, 'storeOrUpdate'])->name('store-or-update');




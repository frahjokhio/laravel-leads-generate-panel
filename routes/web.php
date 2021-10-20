<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;

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



Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
    return view('welcome');
});

/*Route::get('/login', function () {
    return view('login');
});
*/
Route::get('login', [ 'as' => 'login', 'uses' => 'App\Http\Controllers\UserController@login']);

Route::post('auth', 'App\Http\Controllers\UserController@dologin');

Route::get('logout', 'App\Http\Controllers\UserController@doLogout' );


Route::get('/clear-cache',function(){
  Cache::flush();
  echo Hash::make('admin');
});

Route::get('/', [ClientController::class, 'allClients'])->name('allClients')->middleware('auth');
Route::get('client/create', [ClientController::class, 'create'])->name('create-client')->middleware('auth');
Route::get('client/edit/{id}', [ClientController::class, 'edit'])->name('edit-client')->middleware('auth');
Route::post('client/store', 'App\Http\Controllers\ClientController@store')->middleware('auth');
Route::get('client/show/{id}', 'App\Http\Controllers\ClientController@show')->middleware('auth');
Route::post('client/update', 'App\Http\Controllers\ClientController@update')->middleware('auth');
Route::post('client/time', 'App\Http\Controllers\ClientController@setExpiryTime')->middleware('auth');
Route::post('client/delete', 'App\Http\Controllers\ClientController@deleteClient')->middleware('auth');

Route::post('link/delete', 'App\Http\Controllers\ClientController@deleteLink')->middleware('auth');

Route::post('settings/update', 'App\Http\Controllers\SettingsController@storeOrUpdate')->middleware('auth');
Route::get('settings', 'App\Http\Controllers\SettingsController@showSettings')->middleware('auth');


Route::get('files/{clinet_id}', 'App\Http\Controllers\FileController@allFiles')->middleware('auth');
Route::get('client/upload-files/{code}', 'App\Http\Controllers\ClientController@allClients');
Route::get('upload/{code}', 'App\Http\Controllers\ClientController@uploadFiles');

Route::get('find_data', 'App\Http\Controllers\FileController@findData_extend')->middleware('auth');

Route::get('/download/{file}', 'App\Http\Controllers\FileController@download')->middleware('auth');


//ajax calls
Route::post('generate-link', 'App\Http\Controllers\ClientController@generateLink');
Route::post('send-email', 'App\Http\Controllers\ClientController@sendEmail');
Route::post('uploads', 'App\Http\Controllers\ClientController@uploads');
Route::post('remove-file', 'App\Http\Controllers\FileController@remove');
Route::post('find_data', 'App\Http\Controllers\FileController@findData');

Route::get('/404', function () {
    return view('link-not-found');
})->name('404');


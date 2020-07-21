<?php

use Illuminate\Http\Request;
Use App\Message;


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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});



Route::post('register', 'Auth\RegisterController@register');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout');


Route::post('webhook/status', 'MessageController@webhookStatus');
Route::post('webhook/message', 'MessageController@webhookMessage');


Route::group(['middleware' => 'auth:api'], function() {
    Route::get('messages', 'MessageController@index');
    Route::get('messages/status', 'MessageController@status');
    Route::get('messages/{message}', 'MessageController@show');
    Route::get('messages/{message}/status', 'MessageController@messageStatus');
    Route::post('messages', 'MessageController@store');
    Route::put('messages/{message}', 'MessageController@update');
    Route::delete('messages/{message}', 'MessageController@delete');
});



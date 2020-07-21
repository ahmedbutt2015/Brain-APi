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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::post('/user-login', 'APIController\UserController@login')->name('user-login');
Route::post('/user-register', 'APIController\UserController@register')->name('user-register');
Route::post('/user-reset-password', 'APIController\UserController@sendResetLink')->name('user-sendLink');
Route::post('/user-update-password', 'APIController\UserController@updatePassword')->name('user-updatePassword');

Route::post('/get-systems', 'APIController\UserController@getSystems')->name('get-all-systems');
Route::post('/store-system', 'APIController\UserController@storeSystem')->name('store-system');
Route::post('/get-history', 'APIController\UserController@getHistory')->name('get-history');
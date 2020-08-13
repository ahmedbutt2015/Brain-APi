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

// TODO: Rehan
// Contacts
Route::get('contacts', 'APIController\ContactsController@getAllContacts');
Route::get('contacts/{id}', 'APIController\ContactsController@getContact');
Route::post('contacts', 'APIController\ContactsController@createContact');
Route::put('contacts/{id}', 'APIController\ContactsController@updateContact');
Route::delete('contacts/{id}', 'APIController\ContactsController@deleteContact');
// End-Contacts
// Family
Route::post('families', 'APIController\FamilyController@createFamily');
Route::get('families', 'APIController\FamilyController@getAllFamilies');
// END-Family

// Addon
Route::post('addons', 'APIController\AddonController@createAddon');
// End-Addon

Route::post('/save-useraddon', 'APIController\UserAddonController@store');

// Interlocutors
Route::get('customers', 'APIController\InterlocutorController@getAllcustomers');
Route::get('customers/{id}', 'APIController\InterlocutorController@getCustomer');
Route::post('/customers', 'APIController\InterlocutorController@store');
Route::put('customers/{id}', 'APIController\InterlocutorController@updateCustomer');
Route::delete('customers/{id}', 'APIController\InterlocutorController@deleteCustomer');
Route::get('customersAuth/{id}', 'APIController\InterlocutorController@getCustomerwithAuthUser');
// End Interlocutors
Route::post('/alphabets', 'APIController\AlphabetController@store');
Route::get('/getalphabets', 'APIController\AlphabetController@getAlphabets');
// Alphabets
// End Alphabets
// End-TODO: Rehan
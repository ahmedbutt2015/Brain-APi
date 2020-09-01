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

Route::post('/restore-system', 'APIController\UserController@restoreSystem')->name('restore-system');
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
Route::get('customersOnline/{id}', 'APIController\InterlocutorController@getOnlineCustomers');
Route::get('getInterlocutorsData', 'APIController\InterlocutorController@getInterlocutorsData');
Route::get('getRelatedCustomer/{id}', 'APIController\InterlocutorController@getRelatedCustomer');
// End Interlocutors
// Alphabets
Route::post('/alphabets', 'APIController\AlphabetController@store');
Route::get('/getalphabets', 'APIController\AlphabetController@getAlphabets');
// End Alphabets
// CSV
Route::post('/save-csv','APIController\CsvController@import');
// END CSV
Route::get('/tagAuth/{id}', 'APIController\TagController@getTagwithAuthUser');
Route::delete('/tags/{id}', 'APIController\TagController@deleteTag');
Route::get('/tag/{id}', 'APIController\TagController@getTag');
Route::post('/save-language-currency', 'APIController\UserController@languageCurrency');
Route::get('/getsystemId/{id}', 'APIController\UserController@getSystemId');
// Interlocutor Code
Route::post('/save-codes','APIController\InterlocutorController@saveCodes');
Route::get('/getCodes/{id}','APIController\InterlocutorController@getCodes');
Route::get('/getSingleCodes/{id}','APIController\InterlocutorController@getSingleCode');
Route::put('codes/{id}', 'APIController\InterlocutorController@updateCode');
Route::delete('/codes/{id}', 'APIController\InterlocutorController@deleteCode');



// End Interlocutor Code
// End-TODO: Rehan

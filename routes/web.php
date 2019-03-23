<?php

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

Auth::routes(['verify' => true]);

Route::middleware(['verified'])->group(function () {
    Route::get('/home', 'HomeController@index')->name('home');
    // Check links
    Route::get('/check', 'LinkController@checkLinks')->name('check-link');
    // Check links
    Route::get('/check/{link}', 'LinkController@checkLink');
    // Link CRUD Page for users
    Route::get('/links', 'LinkController@index');
    // Delete the link by its owner
    Route::delete('/links/{link}', 'LinkController@delete');
    // Update the link by its owner
    Route::put('/links/{link}', 'LinkController@update');
    // Create the link
    Route::post('/links', 'LinkController@create');
    // Get links with pagination
    Route::get('/link', 'LinkController@get');
    // Get checkout time
    Route::get('/checkout-time', 'LinkController@getCheckoutTime');
    // Set checkout time
    Route::post('/checkout-time', 'LinkController@setCheckoutTime');
});

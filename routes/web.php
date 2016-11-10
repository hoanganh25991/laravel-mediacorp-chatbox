<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index');

Route::get('excel/load', 'ExcelController@load');
Route::post('excel/load', 'ExcelController@load');

Route::get('script', 'ConversationController@script');
Route::post('script', 'ConversationController@script');

Route::group([
    'prefix' => 'api'
], function(){
    Route::get('register', 'RegisterController@regis');
    Route::post('register', 'RegisterController@regis');

    Route::get('update', 'RegisterController@update');
    Route::post('update', 'RegisterController@update');

    Route::group(['middleware' => 'token'], function(){
        Route::get('script', 'ConversationController@script');
        Route::post('script', 'ConversationController@script');
    });
});

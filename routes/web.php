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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::post('/get-friends','HomeController@getFriends');
Route::post('/session/create','SessionController@create');
Route::post('/session/{session}/send','ChatController@send');
Route::get('/session/{session}/chats','ChatController@getChats');

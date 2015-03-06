<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', 'FriendController@getList');


Route::group(array('prefix' => 'user','before' => 'auth.user.isOut'),function()
{
	Route::post('create','UserController@postCreate');
	Route::get('login','UserController@getLogin');
	Route::get('create','UserController@getCreate');
});

Route::group(array('prefix' => 'user','before' => 'auth.user.isIn'),function()
{
	Route::get('logout','UserController@getLogout');
	Route::post('update','UserController@postUpdate');
	Route::post('change-password','UserController@postChangePassword');
	Route::post('delete','UserController@postDelete');
});

Route::group(array('prefix' => 'message','before' => 'auth.user.isIn'),function()
{
	Route::post('send','MessageController@postSend');
	Route::get('read','MessageController@getRead');
	Route::get('hasread','MessageController@getHasRead');
});

Route::group(array('prefix' => 'friend','before' => 'auth.user.isIn'),function()
{
	Route::post('create','FriendController@postCreate');
	Route::post('delete','FriendController@postDelete');
	Route::get('list','FriendController@getList');
});


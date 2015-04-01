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

Route::get('/', 'UserController@index');


Route::group(array('prefix' => 'user'),function()
{
	Route::get('email','UserController@getEmail');
	Route::get('token','WorkController@getToken');
	Route::post('create','UserController@postCreate');
	Route::get('login','UserController@getLogin');
	Route::get('create','UserController@getCreate');
	Route::get('forgetpassword','UserController@getForgetPassword');
	Route::get('checktoken','UserController@getCheckToken');
});

Route::group(array('prefix' => 'user','before' => 'auth.user.isIn'),function()
{
	Route::get('logout','UserController@getLogout');
	Route::post('update','UserController@postUpdate');
	Route::post('change-password','UserController@postChangePassword');
	Route::post('delete','UserController@postDelete');
	Route::get('master','UserController@getMaster');
	Route::get('user','UserController@getUser');
});

Route::group(array('prefix' => 'message','before' => 'auth.user.isIn'),function()
{
	Route::post('send','MessageController@postSend');
	Route::get('read','MessageController@getRead');
	Route::get('hasread','MessageController@getHasRead');
});

Route::group(array('prefix' => 'news','before' => 'auth.user.isIn'),function()
{
	Route::post('read','NewsController@postRead');
	Route::get('list','NewsController@getList');
	Route::get('noread','NewsController@getNoRead');
});

Route::group(array('prefix' => 'friend','before' => 'auth.user.isIn'),function()
{
	Route::post('create','FriendController@postCreate');
	Route::post('delete','FriendController@postDelete');
	Route::get('list','FriendController@getAttentionList');
	Route::get('fans','FriendController@getFans');
});

Route::group(array('prefix' => 'like','before' => 'auth.user.isIn'),function()
{
	Route::post('create','LikeController@postCreate');
	Route::post('delete','LikeController@postDelete');
	Route::get('list','LikeController@getList');
});

Route::group(array('prefix' => 'comment','before' => 'auth.user.isIn'),function()
{
	Route::post('create','CommentController@postCreate');
	Route::post('delete','CommentController@postDelete');
	Route::get('read','CommentController@getRead');
	Route::get('hasread','CommentController@getHasRead');
});

Route::group(array('prefix' => 'rabbitpre','before' => 'auth.user.isIn'),function()
{
	Route::get('token','WorkController@getToken');
	Route::post('create','WorkController@postCreate');
	Route::post('publish','WorkController@postPublish');
	Route::get('list','WorkController@getList');
	Route::post('delete','WorkController@postDelete');
	Route::post('update','WorkController@postUpdate');
	Route::get('albums','WorkController@getAlbums');
	Route::get('work','WorkController@getWork');
	Route::get('score','WorkController@getScore');
});


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


//Route::get('article/index/{base}/{currentPath}/{dataLevel}', array('as' => 'getIndex', 'uses' => 'articleController@getIndex'));



Route::controller('article', 'articleController');
Route::controller('users', 'usersController');
Route::resource('template', 'templateController');

Route::get('/', function()
{
	return View::make('weclome')->with('pageTitle', 'SugarCRM Douglas');
});

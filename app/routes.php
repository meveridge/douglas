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

Route::get('/', function()
{
	return View::make('weclome')->with('pageTitle', 'SugarCRM Douglas');;
});

//Test Only:
/*
Route::get('/article', function()
{
    return View::make('weclome', array(
    	'pageTitle' => 'SugarCRM Douglas',
    	'activeLink' => 'article',
    	)
    );
});
*/
//Route::resource('article','articleController');
//Route::get('/article/path={path}', 'articleController@'


//Working:
/*
Route::get('/article/editArticle/{slashData?}','articleController@editArticle')->where('slashData', '(.*)');
*/
Route::controller('article', 'articleController');
Route::controller('user', 'userController');
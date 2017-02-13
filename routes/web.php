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
    return view('index');
});

Auth::routes();

Route::group(['prefix' => 'api'], function () {

    Route::get('overview', 'OverviewController@getArticleTree');

    Route::get('overview/my', 'OverviewController@getMyArticles');

    Route::resource('sections', 'SectionController');

    Route::resource('articles', 'ArticleController');

    Route::get('artnr/{artnr}', 'ArticleController@showByArticleNr');

    Route::resource('categories', 'CategoryController');

    Route::resource('images', 'ImageController');

    Route::resource('users', 'UserController');

    Route::get('user', 'UserController@me');

    Route::get('contacts', 'UserController@contacts');

    Route::get('roles', 'UserController@rolesList');

    Route::get('token', 'UserController@token');

    // let API know that the route wasn´t found
    Route::any( '{catchall}', function ( $page ) {
        abort(404, $page . ' not found' );
    } )->where('catchall', '(.*)');

});

// Let index handle further routing
Route::any( '{catchall}', function ( $page ) {
    return view('index');
} )->where('catchall', '(.*)');

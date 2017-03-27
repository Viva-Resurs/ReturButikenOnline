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

Auth::routes();

// User interface
Route::get('/ui', function () {
    return view('ui');
});

Route::group(['prefix' => 'ui'], function () {
    // Let index handle further routing
    Route::any( '{catchall}', function ( $page ) {
        return view('ui');
    } )->where('catchall', '(.*)');

});

// API
Route::group(['prefix' => 'api'], function () {

    // Models
    Route::resource('sections', 'SectionController');
    Route::resource('articles', 'ArticleController');
    Route::resource('categories', 'CategoryController');
    Route::resource('users', 'UserController');
    Route::resource('images', 'ImageController');
    Route::resource('shop', 'ShopController');

    // Current user & helpers
    Route::get('overview', 'OverviewController@getArticleTree');
    Route::get('overview/my', 'OverviewController@getMyArticles');
    Route::get('user', 'UserController@me');
    Route::get('contacts', 'UserController@contacts');
    Route::get('roles', 'UserController@rolesList');
    Route::get('token', 'UserController@token');

    // let API know that the route wasn´t found
    Route::any( '{catchall}', function ( $page ) {
        abort(404, $page . ' not found' );
    } )->where('catchall', '(.*)');

});

// Shop
Route::get('/{articleNR?}', function ($articleNR = null) {
    return view('butik', ["articleNR" => $articleNR]);
});

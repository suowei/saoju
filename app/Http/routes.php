<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'IndexController@index');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

Route::group(['middleware' => 'auth'], function()
{
    Route::get('/user/edit', ['as' => 'user.edit', 'uses' => 'UserController@edit']);
    Route::put('/user/update', ['as' => 'user.update', 'uses' => 'UserController@update']);
    Route::get('/user/editpassword', ['as' => 'user.editPassword', 'uses' => 'UserController@editPassword']);
    Route::put('/user/updatePassword', ['as' => 'user.updatPassword', 'uses' => 'UserController@updatePassword']);
    Route::get('/user/export/reviews', ['as' => 'user.export.reviews', 'uses' => 'UserController@exportReviews']);
    Route::get('/user/export/favorites', ['as' => 'user.export.favorites', 'uses' => 'UserController@exportFavorites']);
});

Route::get('/user/{id}', ['as' => 'user.show', 'uses' => 'UserController@show']);
Route::get('/user/{id}/favorites/{type}', ['as' => 'user.favorites', 'uses' => 'UserController@favorites']);
Route::get('/user/{id}/reviews', ['as' => 'user.reviews', 'uses' => 'UserController@reviews']);

Route::get('/drama/search', 'DramaController@search');
Route::get('/drama/{id}/reviews', ['as' => 'drama.reviews', 'uses' => 'DramaController@reviews']);
Route::get('/drama/{id}/histories', ['as' => 'drama.histories', 'uses' => 'DramaController@histories']);
Route::get('/drama/{id}/favorites', ['as' => 'drama.favorites', 'uses' => 'DramaController@favorites']);
Route::resource('drama', 'DramaController');

Route::get('/episode/{id}/reviews', ['as' => 'episode.reviews', 'uses' => 'EpisodeController@reviews']);
Route::get('/episode/{id}/histories', ['as' => 'episode.histories', 'uses' => 'EpisodeController@histories']);
Route::resource('episode', 'EpisodeController');

Route::resource('review', 'ReviewController');

Route::get('/search', 'SearchController@index');

Route::resource('favorite', 'FavoriteController');

//Route::resource('reply', 'ReplyController');

Route::resource('playlist', 'PlaylistController');

Route::group(['prefix' => 'bbs'], function()
{
    Route::get('/', ['as' => 'bbs.index', 'uses' => 'TopicController@index']);
    Route::resource('topic', 'TopicController');
    Route::resource('comment', 'CommentController');
});

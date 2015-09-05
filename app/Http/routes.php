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

Route::post('auth/inviteRegister', 'Auth\AuthController@inviteRegister');
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
    Route::get('/user/export/screvs', ['as' => 'user.export.screvs', 'uses' => 'UserController@exportScrevs']);
    Route::get('/user/invite', ['as' => 'user.invite', 'uses' => 'UserController@invite']);
    Route::put('/user/updateCode', ['as' => 'user.updateCode', 'uses' => 'UserController@updateCode']);
});

Route::get('/user/{id}', ['as' => 'user.show', 'uses' => 'UserController@show']);
Route::get('/user/{id}/epfavs/{type}', ['as' => 'user.epfavs', 'uses' => 'UserController@epfavs']);
Route::get('/user/{id}/favorites/{type}', ['as' => 'user.favorites', 'uses' => 'UserController@favorites']);
Route::get('/user/{id}/reviews', ['as' => 'user.reviews', 'uses' => 'UserController@reviews']);
Route::get('/user/{id}/screvs', ['as' => 'user.screvs', 'uses' => 'UserController@screvs']);
Route::get('/user/{id}/lists', ['as' => 'user.lists', 'uses' => 'UserController@lists']);

Route::get('/drama/search', 'DramaController@search');
Route::get('/drama/{id}/reviews', ['as' => 'drama.reviews', 'uses' => 'DramaController@reviews']);
Route::get('/drama/{id}/histories', ['as' => 'drama.histories', 'uses' => 'DramaController@histories']);
Route::get('/drama/{id}/favorites', ['as' => 'drama.favorites', 'uses' => 'DramaController@favorites']);
Route::get('/drama/{id}/sc', ['as' => 'drama.sc', 'uses' => 'DramaController@sc']);
Route::get('/drama/{id}/versions', ['as' => 'drama.versions', 'uses' => 'DramaController@versions']);
Route::get('/drama/{id}/lists', ['as' => 'drama.lists', 'uses' => 'DramaController@lists']);
Route::resource('drama', 'DramaController');

Route::get('/episode/{id}/reviews', ['as' => 'episode.reviews', 'uses' => 'EpisodeController@reviews']);
Route::get('/episode/{id}/histories', ['as' => 'episode.histories', 'uses' => 'EpisodeController@histories']);
Route::get('/episode/{id}/favorites', ['as' => 'episode.favorites', 'uses' => 'EpisodeController@favorites']);
Route::get('/episode/{id}/sc', ['as' => 'episode.sc', 'uses' => 'EpisodeController@sc']);
Route::get('/episode/{id}/copysc', 'EpisodeController@copysc');
Route::post('/episode/{id}/copysc', 'EpisodeController@storesc');
Route::get('/episode/{id}/versions', ['as' => 'episode.versions', 'uses' => 'EpisodeController@versions']);
Route::get('/episode/{id}/lists', ['as' => 'episode.lists', 'uses' => 'EpisodeController@lists']);
Route::resource('episode', 'EpisodeController');

Route::resource('review', 'ReviewController');

Route::get('/search', 'SearchController@index');

Route::post('/favorite2', 'FavoriteController@store2');
Route::put('/favorite2/{id}', 'FavoriteController@update2');
Route::resource('favorite', 'FavoriteController');

Route::post('/epfav2', 'EpfavController@store2');
Route::put('/epfav2/{id}', 'EpfavController@update2');
Route::resource('epfav', 'EpfavController');

Route::group(['prefix' => 'bbs'], function()
{
    Route::get('/', ['as' => 'bbs.index', 'uses' => 'TopicController@index']);
    Route::resource('topic', 'TopicController');
    Route::resource('comment', 'CommentController');
});

Route::get('/club/search', 'ClubController@search');
Route::get('/club/{id}/versions', ['as' => 'club.versions', 'uses' => 'ClubController@versions']);
Route::resource('club', 'ClubController');

Route::get('/sc/search', 'ScController@search');
Route::get('/sc/{id}/episodes', ['as' => 'sc.episodes', 'uses' => 'ScController@episodes']);
Route::get('/sc/{id}/dramas', ['as' => 'sc.dramas', 'uses' => 'ScController@dramas']);
Route::get('/sc/{id}/versions', ['as' => 'sc.versions', 'uses' => 'ScController@versions']);
Route::resource('sc', 'ScController');

Route::resource('screv', 'ScrevController');

Route::resource('role', 'RoleController');

Route::resource('list', 'ListController');

Route::resource('item', 'ItemController');

Route::get('/listfav/delete', ['as' => 'listfav.delete', 'uses' => 'ListfavController@destroy']);
Route::resource('listfav', 'ListfavController');

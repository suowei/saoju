<?php

Route::get('/', 'IndexController@index');
Route::get('/reviews', 'IndexController@reviews');
Route::get('/zhoubian', 'IndexController@zhoubian');

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
    Route::get('/user/export/epfavs', ['as' => 'user.export.epfavs', 'uses' => 'UserController@exportEpfavs']);
    Route::get('/user/export/screvs', ['as' => 'user.export.screvs', 'uses' => 'UserController@exportScrevs']);
    Route::get('/user/invite', ['as' => 'user.invite', 'uses' => 'UserController@invite']);
    Route::put('/user/updateCode', ['as' => 'user.updateCode', 'uses' => 'UserController@updateCode']);
    Route::get('/user/listfavs', ['as' => 'user.listfavs', 'uses' => 'UserController@listfavs']);
    Route::get('/user/dramafeed', ['as' => 'user.dramafeed', 'uses' => 'UserController@dramafeed']);
});

Route::get('/user/{id}', ['as' => 'user.show', 'uses' => 'UserController@show']);
Route::get('/user/{id}/epfavs/{type}', ['as' => 'user.epfavs', 'uses' => 'UserController@epfavs']);
Route::get('/user/{id}/favorites', ['as' => 'user.favall', 'uses' => 'UserController@favall']);
Route::get('/user/{id}/favorites/{type}', ['as' => 'user.favorites', 'uses' => 'UserController@favorites']);
Route::get('/user/{id}/reviews', ['as' => 'user.reviews', 'uses' => 'UserController@reviews']);
Route::get('/user/{id}/screvs', ['as' => 'user.screvs', 'uses' => 'UserController@screvs']);
Route::get('/user/{id}/lists', ['as' => 'user.lists', 'uses' => 'UserController@lists']);
Route::get('/user/{id}/tags', ['as' => 'user.tags', 'uses' => 'UserController@tags']);
Route::get('/user/{id}/songrevs', ['as' => 'user.songrevs', 'uses' => 'UserController@songrevs']);
Route::get('/user/{id}/songfavs', ['as' => 'user.songfavs', 'uses' => 'UserController@songfavs']);
Route::get('/user/{id}/ftrevs', ['as' => 'user.ftrevs', 'uses' => 'UserController@ftrevs']);
Route::get('/user/{id}/ftfavs', ['as' => 'user.ftfavs', 'uses' => 'UserController@ftfavs']);
Route::get('/user/{id}/ftepfavs', ['as' => 'user.ftepfavs', 'uses' => 'UserController@ftepfavs']);
Route::get('/user/{id}/liverevs', ['as' => 'user.liverevs', 'uses' => 'UserController@liverevs']);
Route::get('/user/{id}/livefavs', ['as' => 'user.livefavs', 'uses' => 'UserController@livefavs']);

Route::get('/drama/search', 'DramaController@search');
Route::get('/drama/{id}/reviews', ['as' => 'drama.reviews', 'uses' => 'DramaController@reviews']);
Route::get('/drama/{id}/histories', ['as' => 'drama.histories', 'uses' => 'DramaController@histories']);
Route::get('/drama/{id}/favorites', ['as' => 'drama.favorites', 'uses' => 'DramaController@favorites']);
Route::get('/drama/{id}/sc', ['as' => 'drama.sc', 'uses' => 'DramaController@sc']);
Route::get('/drama/{id}/versions', ['as' => 'drama.versions', 'uses' => 'DramaController@versions']);
Route::get('/drama/{id}/lists', ['as' => 'drama.lists', 'uses' => 'DramaController@lists']);
Route::get('/drama/{id}/tags', ['as' => 'drama.tags', 'uses' => 'DramaController@tags']);
Route::get('/drama/tag/{tag}', ['as' => 'drama.tag', 'uses' => 'DramaController@tag']);
Route::get('/drama/{id}/songs', ['as' => 'drama.songs', 'uses' => 'DramaController@songs']);
Route::resource('drama', 'DramaController');

Route::get('/episode/{id}/reviews', ['as' => 'episode.reviews', 'uses' => 'EpisodeController@reviews']);
Route::get('/episode/{id}/histories', ['as' => 'episode.histories', 'uses' => 'EpisodeController@histories']);
Route::get('/episode/{id}/favorites', ['as' => 'episode.favorites', 'uses' => 'EpisodeController@favorites']);
Route::get('/episode/{id}/sc', ['as' => 'episode.sc', 'uses' => 'EpisodeController@sc']);
Route::get('/episode/{id}/copysc', 'EpisodeController@copysc');
Route::post('/episode/{id}/copysc', 'EpisodeController@storesc');
Route::get('/episode/{id}/versions', ['as' => 'episode.versions', 'uses' => 'EpisodeController@versions']);
Route::get('/episode/{id}/lists', ['as' => 'episode.lists', 'uses' => 'EpisodeController@lists']);
Route::get('/episode/{id}/songs', ['as' => 'episode.songs', 'uses' => 'EpisodeController@songs']);
Route::get('/episode/everydaylist/{date}', 'EpisodeController@everydayList');
Route::resource('episode', 'EpisodeController');

Route::resource('review', 'ReviewController');

Route::get('/search/tag', ['as' => 'search.tag', 'uses' => 'SearchController@tag']);
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

Route::get('/song/{id}/reviews', ['as' => 'song.reviews', 'uses' => 'SongController@reviews']);
Route::get('/song/{id}/favorites', ['as' => 'song.favorites', 'uses' => 'SongController@favorites']);
Route::get('/song/{id}/versions', ['as' => 'song.versions', 'uses' => 'SongController@versions']);
Route::resource('song', 'SongController');

Route::get('/songfav/store', 'SongfavController@store');
Route::post('/songfav2', 'SongfavController@store2');
Route::resource('songfav', 'SongfavController');

Route::resource('songrev', 'SongrevController');

Route::get('/ft/{id}/reviews', ['as' => 'ft.reviews', 'uses' => 'FtController@reviews']);
Route::get('/ft/{id}/favorites', ['as' => 'ft.favorites', 'uses' => 'FtController@favorites']);
Route::get('/ft/{id}/versions', ['as' => 'ft.versions', 'uses' => 'FtController@versions']);
Route::resource('ft', 'FtController');

Route::get('/ftep/{id}/reviews', ['as' => 'ftep.reviews', 'uses' => 'FtepController@reviews']);
Route::get('/ftep/{id}/favorites', ['as' => 'ftep.favorites', 'uses' => 'FtepController@favorites']);
Route::get('/ftep/{id}/versions', ['as' => 'ftep.versions', 'uses' => 'FtepController@versions']);
Route::resource('ftep', 'FtepController');

Route::get('/ftfav/store', 'FtfavController@store');
Route::post('/ftfav2', 'FtfavController@store2');
Route::resource('ftfav', 'FtfavController');

Route::get('/ftepfav/store', 'FtepfavController@store');
Route::post('/ftepfav2', 'FtepfavController@store2');
Route::resource('ftepfav', 'FtepfavController');

Route::resource('ftrev', 'FtrevController');

Route::get('/live/{id}/reviews', ['as' => 'live.reviews', 'uses' => 'LiveController@reviews']);
Route::get('/live/{id}/favorites', ['as' => 'live.favorites', 'uses' => 'LiveController@favorites']);
Route::get('/live/{id}/versions', ['as' => 'live.versions', 'uses' => 'LiveController@versions']);
Route::resource('live', 'LiveController');

Route::get('/livefav/store', 'LivefavController@store');
Route::post('/livefav2', 'LivefavController@store2');
Route::resource('livefav', 'LivefavController');

Route::resource('liverev', 'LiverevController');

Route::resource('ed', 'EdController');

Route::get('/guide', 'IndexController@guide');

Route::group(['prefix' => 'admin', 'middleware' => 'admin'], function()
{
    Route::get('/', 'AdminController@index');
    Route::post('/deletereview', 'AdminController@deleteReview');
});

Route::group(['prefix' => 'api'], function()
{
    Route::get('/newepisodes', 'Api\IndexController@episodes');
    Route::get('/lists', 'Api\IndexController@lists');
    Route::get('/search', 'Api\IndexController@search');
    Route::get('/csrftoken', 'Api\IndexController@csrftoken');

    Route::post('/auth/login', 'Api\AuthController@login');
    Route::post('/auth/register', 'Api\AuthController@register');

    Route::get('/user/{id}', 'Api\UserController@show');
    Route::get('/user/{id}/favorites/{type}', 'Api\UserController@favorites');
    Route::get('/user/{id}/epfavs/{type}', 'Api\UserController@epfavs');
    Route::get('/user/{id}/reviews',  'Api\UserController@reviews');

    Route::get('/drama/{id}/reviews', 'Api\DramaController@reviews');
    Route::resource('drama', 'Api\DramaController');

    Route::get('/episode/{id}/reviews', 'Api\EpisodeController@reviews');
    Route::resource('episode', 'Api\EpisodeController');

    Route::resource('review', 'Api\ReviewController');

    Route::post('/favorite2', 'Api\FavoriteController@store2');
    Route::put('/favorite2/{id}', 'Api\FavoriteController@update2');
    Route::resource('favorite', 'Api\FavoriteController');

    Route::post('/epfav2', 'Api\EpfavController@store2');
    Route::put('/epfav2/{id}', 'Api\EpfavController@update2');
    Route::resource('epfav', 'Api\EpfavController');
});

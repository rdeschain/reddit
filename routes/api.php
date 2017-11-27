<?php

use \Illuminate\Http\Response;

/**
 * all v1 routing
 */

Route::group(['prefix' => 'v1'], function () {


    Route::group(['middleware' => 'user'], function () {

        Route::put('users/register', 'UserController@register');
        Route::post('users/login', 'UserController@login');

    });

    //requires access token
    Route::group(['middleware' => 'token'], function () {

        Route::get('reddit', 'RedditController@redditList');
        Route::get('reddit/favorites', 'RedditController@redditFavorites');
        Route::get('reddit/favorites/tags', 'RedditController@redditFavoritesByTags');

        Route::put('reddit/{id}/tags', 'RedditController@redditAddTags');

    });

    //v1 catch all
    Route::any('{catchall}', function () {
        return Response::create(['error' => 'Bad HTTP verb or route.'], 404);
    });
});


//absolute catch all
Route::any('{catchall}', function () {
    return Response::create(['error' => 'No route like that found.'], 404);
});

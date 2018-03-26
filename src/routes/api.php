<?php
/**
 * Created by PhpStorm.
 * User: feikwok
 * Date: 24/3/18
 * Time: 11:53 PM
 */

    Route::group(['prefix' => 'api/lmailchimp'], function () {
        Route::get('lists', 'Feikwok\LMailChimp\Http\Controllers\Api\ListApiController@index');
        Route::post('lists', 'Feikwok\LMailChimp\Http\Controllers\Api\ListApiController@store');
        Route::post('lists/{list_id}', 'Feikwok\LMailChimp\Http\Controllers\Api\ListApiController@update');
        Route::delete('lists/{list_id}', 'Feikwok\LMailChimp\Http\Controllers\Api\ListApiController@delete');

        Route::post('lists/{list_id}/members', 'Feikwok\LMailChimp\Http\Controllers\Api\MemberApiController@store');
        Route::put('lists/{list_id}/members', 'Feikwok\LMailChimp\Http\Controllers\Api\MemberApiController@update');
        Route::delete('lists/{list_id}/members', 'Feikwok\LMailChimp\Http\Controllers\Api\MemberApiController@delete');
    });
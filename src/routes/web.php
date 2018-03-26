<?php
/**
 * Created by PhpStorm.
 * User: feikwok
 * Date: 25/3/18
 * Time: 2:45 PM
 */

Route::group(['prefix' => 'lmailchimp'], function () {
    Route::all('/', \Feikwok\LMailChimp\Http\Controllers\DashboardController::index);
});
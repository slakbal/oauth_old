<?php

Route::group(['namespace' => 'Slakbal\Oauth\Controllers', 'prefix' => 'oauth2', 'middleware' => ['web']], function () {

    Route::get('{provider}', 'OAuthController@redirectToProvider')->name('oauth.redirect');
    Route::get('{provider}/callback', 'OAuthController@handleProviderCallback')->name('oauth.callback');

});

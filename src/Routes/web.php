<?php

Route::group([
    'namespace' => 'Slakbal\Oauth\Controllers',
    'prefix' => 'oauth2',
    'middleware' => ['web']
], function () {

    Route::match(['get', 'post'], '{provider}/login', 'OAuthController@redirectToProvider')->name('oauth.redirect');

    Route::match(['get', 'post'], 'callback', 'OAuthController@handleProviderCallback')->name('oauth.callback');

});

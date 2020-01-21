<?php

Route::group([
    'namespace' => 'Slakbal\Oauth\Controllers',
    'prefix' => 'oauth2',
    'middleware' => ['web']
], function () {

    Route::match(['get', 'post'], '{provider}', 'OAuthController@redirectToProvider')->name('oauth.redirect');

    Route::match(['get', 'post'], '{provider}/callback', 'OAuthController@handleProviderCallback')->name('oauth.callback');

});

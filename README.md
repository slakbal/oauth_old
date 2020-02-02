# Very short description of the package

## Installation

Setup Routes that point to your login controller:

```php
Route::group([
    'prefix' => 'oauth2',
], function () {

    Route::match(['get', 'post'], '{provider}', 'LoginController@redirectToProvider')->name('oauth.redirect');

    Route::match(['get', 'post'], '{provider}/callback', 'LoginController@handleProviderCallback')->name('oauth.callback');

});
```

## Config

Add to the config/services.php file an entry for each of the specific providers

```php
    '{provider}' => [
        'client_id' => 'the_client_id',
        'client_secret' => 'the_client_secret',
        'redirect' => 'https://application.app/oauth2/{provider}/callback',
    ],
```

### Config

Publish the config and add an entry for the allowed provider_name

```php
return [
    'providers' => [
        'allowed' => [
            '{provider}',
        ],
    ]
];
```

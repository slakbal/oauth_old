<?php

namespace Slakbal\Oauth;

use Laravel\Socialite\Facades\Socialite;
use Slakbal\Oauth\Exception\OAuthException;

class Oauth
{
    public function redirectToProvider($provider){
        return Socialite::driver($this->providerIsAllowed($provider))->redirect();
    }

    public function user($provider){
        return Socialite::driver($this->providerIsAllowed($provider))->user();
    }

    private function providerIsAllowed($provider)
    {
        $provider = $this->sanitizeValue($provider);

        if (!in_array($provider, config('oauth.providers.allowed'))) {
            throw OAuthException::providerNotSupported($provider);
        }

        return $provider;
    }

    private function sanitizeValue($value)
    {
        return strtolower(trim($value));
    }
}

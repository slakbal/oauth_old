<?php

namespace Slakbal\Oauth;

use Laravel\Socialite\Facades\Socialite;
use Slakbal\Oauth\Exception\OAuthException;

class Oauth
{
    public function redirectToProvider($provider)
    {
        return Socialite::driver($this->providerAllowed($provider))->redirect();
    }

    public function user($provider)
    {
        return Socialite::driver($this->providerAllowed($provider))->user();
    }

    private function providerAllowed($provider)
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

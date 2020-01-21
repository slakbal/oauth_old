<?php

namespace Slakbal\Oauth\Controllers;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;
use Slakbal\Oauth\Exception\OAuthException;

class OAuthController extends Controller
{

    public function redirectToProvider($provider)
    {
        return Socialite::driver($this->ProviderIsAllowed($provider))->redirect();
    }


    public function handleProviderCallback($provider)
    {
        $user = Socialite::driver($this->ProviderIsAllowed($provider))->user();

        dd($user);

        // Update Or Create User

        //throw create or update event

        // Add token

        //throw login event

    }


    private function ProviderIsAllowed($provider)
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

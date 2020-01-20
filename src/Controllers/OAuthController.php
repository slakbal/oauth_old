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


    public function handleProviderCallback()
    {
        $provider = 'siv';

        try {
            $user = Socialite::driver($provider)->user();
        } catch (InvalidStateException $e) {
            $user = Socialite::driver($provider)->stateless()->user();
        }

    }

}

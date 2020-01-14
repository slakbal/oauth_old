<?php

namespace Slakbal\Oauth\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
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

    public function handleProviderCallback(Request $request)
    {
        dd($request);
    }
}

<?php

namespace Slakbal\Oauth\Controllers;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;
use Slakbal\Oauth\Exception\OAuthException;
use Slakbal\Oauth\Providers\Siv\User;

class OAuthController extends Controller
{

    public function redirectToProvider($provider)
    {
        return Socialite::driver($this->ProviderIsAllowed($provider))->redirect();
    }


    public function handleProviderCallback($provider)
    {
        $siv_user = Socialite::driver($this->ProviderIsAllowed($provider))->user();

        $user = $this->userFindOrCreate($siv_user, $provider);


        // Add token

        //throw login event

    }


    private function userFindOrCreate(User $user, $provider)
    {
        dd($user->getLastname().', '.$user->getFirstname());
        return \App\User::firstOrCreate([
            'email' => $user->getEmail()
        ],[
            'email' => $user->getEmail(),

        ]);

        //throw create or update event


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

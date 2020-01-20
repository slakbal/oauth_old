<?php

namespace Slakbal\Oauth\SocialiteProviders\Siv;

use SocialiteProviders\Manager\OAuth2\AbstractProvider;
use SocialiteProviders\Manager\OAuth2\User;

class Provider extends AbstractProvider
{
    /**
     * Unique Provider Identifier.
     */
    const IDENTIFIER = 'SIV';

    private const BASEURL_PROD = 'https://accounts.siv.de';

    private const BASEURL_TEST = 'https://accounts-test.siv.de';


    /**
     * {@inheritdoc}
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase($this->getBaseUrl() . '/accounts/authorize', $state);
    }


    /**
     * The scopes being requested.
     *
     * @var array
     */
    protected $scopes = [
        'openid',
//        'email',
//        'profile',
    ];

    /**
     * {@inheritdoc}
     */
    protected function getTokenUrl()
    {
        return $this->getBaseUrl() . '/accounts/token';
    }

    /**
     * {@inheritdoc}
     */
    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get($this->getBaseUrl() . '/accounts/userinfo', [
            'headers' => [
                //Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $token,
            ],
        ]);

        //todo inspect response here
//        dd(json_decode($response->getBody(), true));
        return json_decode($response->getBody(), true);
    }

    /**
     * {@inheritdoc}
     */
    protected function mapUserToObject(array $user)
    {
        //todo inspect here
        dd($user);
        return (new User())->setRaw($user)->map([
            'id' => $user['id'],
            'nickname' => $user['username'],
            'name' => $user['name'],
            'email' => $user['email'],
            'avatar' => $user['avatar'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenFields($code)
    {
        return array_merge(parent::getTokenFields($code), [
            'grant_type' => 'authorization_code'
        ]);
    }

    protected function getBaseUrl()
    {
        if (app()->environment('production')) {
            return self::BASEURL_PROD;
        }

        return self::BASEURL_TEST;
    }
}

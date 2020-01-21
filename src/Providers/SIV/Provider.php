<?php

namespace Slakbal\Oauth\Providers\Siv;

use Illuminate\Support\Arr;
use Laravel\Socialite\Two\ProviderInterface;
use SocialiteProviders\Manager\OAuth2\AbstractProvider;
use SocialiteProviders\Manager\OAuth2\User;

class Provider extends AbstractProvider implements ProviderInterface
{
    /**
     * Unique Provider Identifier.
     */
    const IDENTIFIER = 'SIV';

    private const BASE_URL = 'https://accounts.siv.de';

    protected $scopeSeparator = '+';
    
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
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase($this->getBaseUrl() . '/accounts/authorize', $state);
    }


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
        $userUrl = self::BASE_URL . '/accounts/userinfo';

        $response = $this->getHttpClient()->post(
            $userUrl, [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
            ],
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * {@inheritdoc}
     */
    protected function mapUserToObject(array $user)
    {
        $firstName = Arr::get($user, 'givenName');
        $lastName = Arr::get($user, 'familyName');

        return (new User)->setRaw($user)->map([
            'id' => Arr::get($user, 'id'),
            'nickname' => $firstName,
            'name' => $firstName . ' ' . $lastName,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => Arr::get($user, 'mail'),
            'avatar' => Arr::get($user, 'avatar_url')
        ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenFields($code)
    {
        return array_merge(parent::getTokenFields($code), [
            'grant_type' => 'authorization_code',
        ]);
    }


    protected function getBaseUrl()
    {
        return self::BASE_URL;
    }

    /*
    // Examples of handling refresh tokens
    public function getRefreshTokenResponse($refreshToken)
    {
        $response = $this->getHttpClient()->post($this->getTokenUrl(), [
            'headers' => ['Accept' => 'application/json'],
            'form_params' => $this->getRefreshTokenFields($refreshToken),
        ]);

        return json_decode($response->getBody(), true);
    }


    public function getRefreshTokenFields($refreshToken)
    {
        return [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken,
        ];
    }
    */
}

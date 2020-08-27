<?php

namespace Slakbal\Oauth\Providers\Siv;

use Illuminate\Support\Arr;
use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\InvalidStateException;
use Laravel\Socialite\Two\ProviderInterface;

class Provider extends AbstractProvider implements ProviderInterface
{
    /**
     * Unique Provider Identifier.
     */
    const IDENTIFIER = 'SIV';

    private const BASE_URL = 'https://accounts.siv.de';

    protected $scopeSeparator = ' ';

    /**
     * The scopes being requested.
     *
     * @var array
     */
    protected $scopes = [
        'openid',
        'profile',
        'email',
    ];

    protected function getBaseUrl()
    {
        return self::BASE_URL;
    }


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
     * Decodes the id_token so that the identity can be extracted from the claims
     *
     * @param null $jwt
     * @return array|false
     */
    public function decode($jwt = null)
    {
        if ($jwt) {
            $jwt = list($header, $claims, $signature) = explode('.', $jwt);

            $header = self::decodeFragment($header);
            $claims = self::decodeFragment($claims);
            $signature = (string)base64_decode($signature);

            return [
                'header' => $header,
                'claims' => $claims,
                'signature' => $signature
            ];
        }

        return false;
    }

    /**
     * Decode the specific fragment from the JWT token
     *
     * @param $value
     * @return array
     */
    protected function decodeFragment($value)
    {
        return (array)json_decode(base64_decode($value));
    }


    /**
     * Decodes the id_token JWT and maps the claims to the same names as what the identity provider
     * end-point of the server would return to ensure compatibility.
     *
     * @param $token
     * @return array
     */
    protected function extractClaimsFromIDToken($token): array
    {
        if ($decodedTokenArray = $this->decode($token)) {
            return [
                'givenName' => $decodedTokenArray['claims']['given_name'], //doing this to be compatible with the identity provider's end-point response in-case it need to be used
                'familyName' => $decodedTokenArray['claims']['family_name'], //doing this to be compatible with the identity provider's end-point response in-case it need to be used
                'mail' => $decodedTokenArray['claims']['mail'],
                /*
                "iat" => $decodedTokenArray['claims']['iat'],
                "exp" => $decodedTokenArray['claims']['exp'],
                "iss" => $decodedTokenArray['claims']['iss'],
                "jti" => $decodedTokenArray['claims']['jti'],
                "kid" => $decodedTokenArray['claims']['kid'],
                "name" => $decodedTokenArray['claims']['name'],
                "sco" => $decodedTokenArray['claims']['sco'],
                "sid" => $decodedTokenArray['claims']['sid'],
                "sub" => $decodedTokenArray['claims']['sub'],
                "typ" => $decodedTokenArray['claims']['typ'],
                "upn" => $decodedTokenArray['claims']['upn'],
                */
            ];
        };

        return [];
    }

    /**
     * Overriding the base User method to make get the user from the JWT id_token (extractClaimsFromIDToken)
     * instead of getUserByToken that pulls it from the identity provider (server) end-point and has a different
     * signature
     *
     * @return \Laravel\Socialite\Contracts\User|User
     */
    public function user()
    {
        if ($this->hasInvalidState()) {
            throw new InvalidStateException;
        }

        $response = $this->getAccessTokenResponse($this->getCode());

        $user = $this->mapUserToObject($this->extractClaimsFromIDToken(
            $idToken = Arr::get($response, 'id_token')
        ));

        return $user->setToken(Arr::get($response, 'access_token'))
            ->setRefreshToken(Arr::get($response, 'refresh_token'))
            ->setExpiresIn(Arr::get($response, 'expires_in'))
            ->setIdToken($idToken);
    }

    /**
     * {@inheritdoc}
     */
    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->post($this->getBaseUrl() . '/accounts/userinfo', [
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $token,
            ],
        ]);

        return json_decode($response->getBody(), true);
    }

    /**
     * {@inheritdoc}
     */
    protected function mapUserToObject(array $user)
    {
        $firstName = Arr::get($user, 'givenName');
        $lastName = Arr::get($user, 'familyName');

        return (new User)->setRaw($user)->map([
            'id' => Arr::get($user, 'id', null),
            'nickname' => $firstName,
            'name' => $firstName . ' ' . $lastName,
            'email' => strtolower(Arr::get($user, 'mail')),
            'avatar' => Arr::get($user, 'avatar_url'),
            'first_name' => $firstName,
            'last_name' => $lastName,
        ]);
    }

    /**
     * Get the POST fields for the token request.
     *
     * @param string $code
     * @return array
     */
    protected function getTokenFields($code)
    {
        return Arr::add(
            parent::getTokenFields($code), 'grant_type', 'authorization_code'
        );
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

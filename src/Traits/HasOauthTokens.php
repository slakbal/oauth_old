<?php

namespace Slakbal\Oauth\Traits;

use Slakbal\OAuth\Models\OAuthToken;

trait HasOauthTokens
{
    /**
     * Get the tokens for the related model.
     */
    public function tokens()
    {
        return $this->hasMany(OAuthToken::class);
    }
}

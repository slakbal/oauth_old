<?php

namespace Slakbal\OAuth\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class OAuthToken extends Model
{
    const SIV = "siv";
    const FACEBOOK = "facebook";
    const GOOGLE = "google";

    protected $table = 'oauth_tokens';

    protected $fillable = [
        'user_id',
        'provider_user_id',
        'provider',
        'access_token',
    ];

    /*
    static function getOAuthTypes()
    {
        $class = new \ReflectionClass(get_called_class());
        return array_keys($class->getConstants());
    }
    */

    /**
     * Get the user model of the token
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

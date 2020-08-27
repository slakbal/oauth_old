<?php

namespace Slakbal\Oauth\Exception;

use Exception;
use Illuminate\Support\Facades\Log;

class OAuthException extends Exception
{
    public static function providerNotSupported($provider)
    {
        $message = __('Possible URL tampering. The `:provider` oauth provider is not supported.', ['provider' => $provider]);

        Log::warning('OAUTH: '.$message);

        return new static($message);
    }
}

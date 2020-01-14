<?php

namespace Slakbal\Oauth;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Slakbal\Oauth\Skeleton\SkeletonClass
 */
class OauthFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'oauth';
    }
}

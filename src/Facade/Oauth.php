<?php

namespace Slakbal\Oauth\Facade;

use Illuminate\Support\Facades\Facade;

class Oauth extends Facade
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

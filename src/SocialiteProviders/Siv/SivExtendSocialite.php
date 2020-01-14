<?php

namespace Slakbal\Oauth\SocialiteProviders\Siv;

use SocialiteProviders\Manager\SocialiteWasCalled;

class SivExtendSocialite
{
    /**
     * Execute the provider.
     */
    public function handle(SocialiteWasCalled $socialiteWasCalled)
    {
        $socialiteWasCalled->extendSocialite('siv', __NAMESPACE__ . '\Provider');
    }
}

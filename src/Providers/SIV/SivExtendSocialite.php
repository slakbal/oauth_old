<?php

namespace Slakbal\Oauth\Providers\Siv;

use SocialiteProviders\Manager\SocialiteWasCalled;

class SivExtendSocialite
{
    /**
     * Execute the provider.
     */
    public function handle(SocialiteWasCalled $socialiteWasCalled)
    {
        $socialiteWasCalled->extendSocialite('siv', __NAMESPACE__.'\Provider');
    }
}

<?php

namespace Slakbal\Oauth\Providers\Siv;

use Laravel\Socialite\Two\User as BaseUser;
use Slakbal\Oauth\Providers\UserInterface;

class User extends BaseUser implements UserInterface
{

    /**
     * @inheritDoc
     */
    public function getFirstname()
    {
        return $this->first_name;
    }


    /**
     * @inheritDoc
     */
    public function getLastname()
    {
        return $this->last_name;
    }

}

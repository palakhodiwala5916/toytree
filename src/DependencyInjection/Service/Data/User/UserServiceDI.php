<?php

namespace App\DependencyInjection\Service\Data\User;

use App\Service\Data\User\UserService;

trait UserServiceDI
{
    protected UserService $userService;

    /**
     * @required
     */
    public function setUserService(UserService $userService)
    {
        $this->userService = $userService;
    }
}

<?php

namespace App\DependencyInjection\Repository\User;

use App\Repository\User\UserRepository;

trait UserRepositoryDI
{
    protected UserRepository $userRepository;

    #[\Symfony\Contracts\Service\Attribute\Required]
    public function setUserRepository(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
}

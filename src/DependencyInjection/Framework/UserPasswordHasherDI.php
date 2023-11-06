<?php

namespace App\DependencyInjection\Framework;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

trait UserPasswordHasherDI
{
    protected UserPasswordHasherInterface $userPasswordHasher;

    #[\Symfony\Contracts\Service\Attribute\Required]
    public function setUserPasswordHasherInterface(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }
}

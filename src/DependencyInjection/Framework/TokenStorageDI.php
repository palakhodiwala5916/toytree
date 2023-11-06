<?php

namespace App\DependencyInjection\Framework;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

trait TokenStorageDI
{
    protected TokenStorageInterface $tokenStorage;

    #[\Symfony\Contracts\Service\Attribute\Required]
    public function setToken(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }
}

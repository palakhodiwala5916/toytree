<?php

namespace App\DependencyInjection\Service\OAuth;

use App\Service\Security\OAuth\AppleOAuthClient;

trait AppleOAuthClientDI
{
    /**
     * @var AppleOAuthClient
     */
    protected AppleOAuthClient $appleOAuthClient;

    #[\Symfony\Contracts\Service\Attribute\Required]
    public function setAppleOAuthClient(AppleOAuthClient $appleOAuthClient)
    {
        $this->appleOAuthClient = $appleOAuthClient;
    }
}
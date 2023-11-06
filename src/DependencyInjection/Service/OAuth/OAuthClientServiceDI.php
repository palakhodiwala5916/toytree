<?php

namespace App\DependencyInjection\Service\OAuth;

use App\Service\Security\OAuth\OAuthClientService;

trait OAuthClientServiceDI
{
    protected OAuthClientService $oAuthClientService;

    #[\Symfony\Contracts\Service\Attribute\Required]
    public function setOAuthClientService(OAuthClientService $oAuthClientService): void
    {
        $this->oAuthClientService = $oAuthClientService;
    }
}

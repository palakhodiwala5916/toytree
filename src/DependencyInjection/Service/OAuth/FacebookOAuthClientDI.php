<?php

namespace App\DependencyInjection\Service\OAuth;

use App\Service\Security\OAuth\FacebookOAuthClient;
use App\Service\Security\SecurityService;

trait FacebookOAuthClientDI
{
    /**
     * @var FacebookOAuthClient
     */
    protected FacebookOAuthClient $facebookOAuthClient;

    #[\Symfony\Contracts\Service\Attribute\Required]
    public function setFacebookOAuthClient(FacebookOAuthClient $facebookOAuthClient)
    {
        $this->facebookOAuthClient = $facebookOAuthClient;
    }
}
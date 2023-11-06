<?php

namespace App\DependencyInjection\Service\OAuth;

use App\Service\Security\OAuth\GoogleOAuthClient;

trait GoogleOAuthClientDI
{
    /**
     * @var GoogleOAuthClient
     */
    protected GoogleOAuthClient $googleOAuthClient;

    #[\Symfony\Contracts\Service\Attribute\Required]
    public function setGoogleOAuthClient(GoogleOAuthClient $googleOAuthClient)
    {
        $this->googleOAuthClient = $googleOAuthClient;
    }
}
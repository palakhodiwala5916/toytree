<?php

namespace App\Service\Security\OAuth;

use App\DependencyInjection\Service\OAuth\AppleOAuthClientDI;
use App\DependencyInjection\Service\OAuth\FacebookOAuthClientDI;
use App\DependencyInjection\Service\OAuth\GoogleOAuthClientDI;
use App\Exception\SoftException;
use App\VO\Protocol\Api\Security\OAuthClientData;

class OAuthClientService
{
    public const FACEBOOK = 'Facebook';
    public const GOOGLE = 'Google';
    public const APPLE = 'Apple';

    use FacebookOAuthClientDI;
    use GoogleOAuthClientDI;
    use AppleOAuthClientDI;

    /**
     * @throws SoftException
     */
    public function verifyToken(string $token, string $clientName): OAuthClientData
    {
        return match ($clientName) {
            OAuthClientService::FACEBOOK => $this->facebookOAuthClient->verifyToken($token),
            OAuthClientService::GOOGLE => $this->googleOAuthClient->verifyToken($token),
            OAuthClientService::APPLE => $this->appleOAuthClient->verifyToken($token),
            default => throw new SoftException("login.request.error.unknown_client_requested"),
        };
    }

}

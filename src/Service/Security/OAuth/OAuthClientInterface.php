<?php

namespace App\Service\Security\OAuth;

use App\VO\Protocol\Api\Security\OAuthClientData;

interface OAuthClientInterface
{
    public function verifyToken(string $token): OAuthClientData;
}
<?php

namespace App\Service\Security\OAuth;

use App\VO\Protocol\Api\Security\OAuthClientData;

class AppleOAuthClient implements  OAuthClientInterface
{

    public function verifyToken(string $token): OAuthClientData
    {
        $user = new OAuthClientData();

        // TODO: Check apple token is valid or not and return OAuthClientData.

        $user->userId = '1303834109';
        $user->email = 'apple.user@gmail.com';
        $user->name = 'Apple User';
        $user->country_code = "IND";
        $user->phone_number = "9999999993";

        return $user;
    }
}
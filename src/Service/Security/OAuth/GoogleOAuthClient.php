<?php

namespace App\Service\Security\OAuth;

use App\VO\Protocol\Api\Security\OAuthClientData;

class GoogleOAuthClient implements  OAuthClientInterface
{

    public function verifyToken(string $token): OAuthClientData
    {
        $user = new OAuthClientData();

        // TODO: Check google token is valid or not and return OAuthClientData.

        $user->userId = '1303834108';
        $user->email = 'google.user@gmail.com';
        $user->name = 'Google User';
        $user->country_code = "IND";
        $user->phone_number = "9999999992";

        return $user;
    }
}
<?php

namespace App\Service\Security\OAuth;

use App\VO\Protocol\Api\Security\OAuthClientData;

class FacebookOAuthClient implements  OAuthClientInterface
{

    public function verifyToken(string $token): OAuthClientData
    {
        $user = new OAuthClientData();

        // TODO: Check facebook token is valid or not and return OAuthClientData.

        $user->userId = '1303834107';
        $user->email = 'facebook.user@gmail.com';
        $user->name = 'Facebook User';
        $user->country_code = "IND";
        $user->phone_number = "9999999991";

        return $user;
    }
}
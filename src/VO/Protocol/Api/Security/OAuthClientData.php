<?php

namespace App\VO\Protocol\Api\Security;

use App\VO\Protocol\ResponseDataInterface;

class OAuthClientData implements ResponseDataInterface
{
    public string $name;

    public string $email;

    public string $userId;

    public string $country_code;

    public string $phone_number;

    public string $role;
}
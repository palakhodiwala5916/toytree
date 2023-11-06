<?php

namespace App\VO\Protocol\Api\Security;

use App\VO\Protocol\RequestBodyInterface;
use Symfony\Component\Validator\Constraints as Assert;

class SocialLoginVO implements RequestBodyInterface
{
    #[Assert\NotBlank(message: 'login.request.token.required')]
    public ?string $token = null;
}

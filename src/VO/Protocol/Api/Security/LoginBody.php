<?php

namespace App\VO\Protocol\Api\Security;

use App\VO\Protocol\RequestBodyInterface;
use Symfony\Component\Validator\Constraints as Assert;

class LoginBody implements RequestBodyInterface
{
    #[Assert\NotBlank(message: 'login.request.username.required')]
    public ?string $username = null;

    #[Assert\NotBlank(message: 'login.request.password.required')]
    public ?string $password = null;
}

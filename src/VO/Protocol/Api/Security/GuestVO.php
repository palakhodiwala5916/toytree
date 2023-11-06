<?php

namespace App\VO\Protocol\Api\Security;

use App\VO\Protocol\RequestBodyInterface;
use Symfony\Component\Validator\Constraints as Assert;

class GuestVO  implements RequestBodyInterface
{
      #[Assert\NotBlank(message: 'register.request.username.required')]
    public ?string $username = null;

      #[Assert\NotBlank(message: 'register.request.password.required')]
      #[Assert\Length(min: 8, minMessage: 'register.request.password.too_short')]
    public ?string $password = null;

    public ?string $userIp = null;

    public ?string $browser = null;

}

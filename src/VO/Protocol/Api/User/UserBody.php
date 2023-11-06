<?php

namespace App\VO\Protocol\Api\User;

use App\VO\Protocol\RequestBodyInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class UserBody implements RequestBodyInterface
{
    #[Assert\NotBlank(message: 'user.update.phone_number.required')]
    public ?string $phoneNumber = null;

    #[Assert\NotBlank(message: 'user.update.country_code.required')]
    public ?string $countryCode = null;
}

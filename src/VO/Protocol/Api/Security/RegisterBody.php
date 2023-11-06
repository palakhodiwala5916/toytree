<?php

namespace App\VO\Protocol\Api\Security;

use App\Entity\User\User;
use App\VO\Protocol\RequestBodyInterface;
use Symfony\Component\Validator\Constraints as Assert;

class RegisterBody implements RequestBodyInterface
{
    #[Assert\NotBlank(message: 'register.request.full_name.required')]
    public string $fullName;

    #[Assert\NotBlank(message: 'register.request.phone_number.required')]
    public string $phoneNumber;

    #[Assert\Choice(
        choices: [User::ROLE_USER],
        message: 'register.request.role.required'
    )]
    public string $role;

    #[Assert\NotBlank(message: 'register.request.username.required')]
    public string $email;

    #[Assert\NotBlank(message: 'register.request.password.required')]
    #[Assert\Length(min: 8, minMessage: 'register.request.password.too_short')]
    public string $password;

    #[Assert\NotBlank(message: 'register.request.confirm_password.required')]
    #[Assert\Length(min: 8, minMessage: 'register.request.password.too_short')]
    public string $confirmPassword;

    public ?string $otp = null;
}

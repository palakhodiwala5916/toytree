<?php

namespace App\VO\Protocol\Api\Security;

use App\Entity\User\ConfirmationCode;
use App\VO\Protocol\RequestBodyInterface;
use Symfony\Component\Validator\Constraints as Assert;

class ResendOTPBody implements RequestBodyInterface
{
    #[Assert\NotBlank(message: 'resendOTP.request.email.required')]
    public string $email;

    #[Assert\NotBlank(message: 'resendOTP.request.action.required')]
    #[Assert\Choice([ConfirmationCode::ACTION_EMAIL_CONFIRMATION, ConfirmationCode::ACTION_PASSWORD_RESET, ConfirmationCode::ACTION_PHONE_CONFIRMATION],
        message: 'resendOTP.request.action.in_valid')]
    public string $action;
}

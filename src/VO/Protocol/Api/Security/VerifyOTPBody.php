<?php

namespace App\VO\Protocol\Api\Security;

use App\Entity\User\ConfirmationCode;
use App\VO\Protocol\RequestBodyInterface;
use Symfony\Component\Validator\Constraints as Assert;

class VerifyOTPBody implements RequestBodyInterface
{
    public ?string $otp = null;

    #[Assert\Choice(choices: [ConfirmationCode::ACTION_EMAIL_CONFIRMATION, ConfirmationCode::ACTION_PASSWORD_RESET, ConfirmationCode::ACTION_PHONE_CONFIRMATION],message: 'verifyOTP.request.action.valid')]
    #[Assert\NotBlank(message: 'verifyOTP.request.action.required')]
    public ?string $action = null;
}

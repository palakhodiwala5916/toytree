<?php

namespace App\VO\Protocol\Api\User;

use App\VO\Protocol\RequestBodyInterface;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

class ResetPasswordVO implements RequestBodyInterface
{
    #[SerializedName('email')]
    #[Assert\NotBlank(message: 'user.reset.confirmation.email.required')]
    #[Assert\Email(message: 'user.reset.confirmation.email.invalid')]
    public string $email;
}

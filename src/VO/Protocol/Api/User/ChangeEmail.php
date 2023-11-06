<?php

namespace App\VO\Protocol\Api\User;
use App\VO\Protocol\RequestBodyInterface;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

final class ChangeEmail implements RequestBodyInterface
{
    #[SerializedName('newEmail')]
    #[Assert\NotBlank(message: 'user.change.email.new_email.required')]
    public string $newEmail;
}
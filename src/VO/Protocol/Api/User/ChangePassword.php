<?php

namespace App\VO\Protocol\Api\User;

use App\VO\Protocol\RequestBodyInterface;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

class ChangePassword implements RequestBodyInterface
{
    #[SerializedName('currentPassword')]
    #[Assert\NotBlank(message: 'user.change.password.currentPassword.required')]
    public string $currentPassword;

    #[SerializedName('newPassword')]
    #[Assert\NotBlank(message: 'user.change.password.newPassword.required')]
    #[Assert\Length(min: 8, minMessage: 'user.change.new.password.minLength')]
    public string $newPassword;

    #[SerializedName('reEnterPassword')]
    #[Assert\NotBlank(message: 'user.change.password.reEnterPassword.required')]
    #[Assert\Length(min: 8, minMessage: 'user.change.re_enter.password.minLength')]
    #[Assert\Expression('value == this.newPassword', message: 'user.change.new.password.notMatch')]
    public string $reEnterPassword;
}
<?php

namespace App\VO\Protocol\Api\User;

use App\VO\Protocol\RequestBodyInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class UserNotificationSettingsBody implements RequestBodyInterface
{
    #[Assert\NotBlank(message: 'user.change.notification.required')]
    public ?string $notification = null;

    public ?bool $value = null;

}

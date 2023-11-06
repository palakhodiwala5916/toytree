<?php

namespace App\VO\Protocol\Api\User;

use App\VO\Protocol\RequestBodyInterface;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

final class UserNotificationBody implements RequestBodyInterface
{
    #[SerializedName('notification')]
    #[Assert\NotBlank(message: 'user.change.notification.required')]
    public ?string $notificationId = null;
}
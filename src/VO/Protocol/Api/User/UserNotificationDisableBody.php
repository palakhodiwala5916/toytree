<?php

namespace App\VO\Protocol\Api\User;

use App\VO\Protocol\RequestBodyInterface;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

final class UserNotificationDisableBody implements RequestBodyInterface
{
    #[SerializedName('notification')]
    #[Assert\NotBlank(message: 'user.change.notification.required')]
    public ?string $notificationId = null;

    #[SerializedName('notificationSetting')]
    #[Assert\NotBlank(message: 'user.change.notification_setting.required')]
    public ?string $notificationSettingId = null;
}
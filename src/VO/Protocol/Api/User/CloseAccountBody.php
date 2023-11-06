<?php

namespace App\VO\Protocol\Api\User;

use App\VO\Protocol\RequestBodyInterface;
use Symfony\Component\Validator\Constraints as Assert;

class CloseAccountBody implements RequestBodyInterface
{
    #[Assert\NotBlank(message: 'user.close.account.reason.required')]
    public ?array $reason = null;

}

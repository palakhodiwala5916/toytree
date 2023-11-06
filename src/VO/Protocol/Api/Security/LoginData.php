<?php

namespace App\VO\Protocol\Api\Security;

use App\Entity\User\User;
use App\VO\Protocol\ResponseDataInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

class LoginData implements ResponseDataInterface
{
    #[Groups(['api'])]
    #[Assert\NotNull(message: 'login.auth.token.invalid')]
    public string $token;

    #[Groups(['api'])]
    public ?string $refreshToken = null;

    #[Groups(['api', 'client:view'])]
    #[Assert\NotNull(message: 'login.auth.user.invalid')]
    public User $user;
}

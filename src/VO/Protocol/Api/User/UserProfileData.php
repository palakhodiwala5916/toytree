<?php

namespace App\VO\Protocol\Api\User;

use App\Entity\User\User;
use App\VO\Protocol\ResponseDataInterface;
use Symfony\Component\Serializer\Annotation\Groups;

class UserProfileData implements ResponseDataInterface
{
    #[Groups(['api'])]
    public ?User $verifiedInfo = null;

    #[Groups(['api'])]
    public array $rating = [];

    public function __construct($verifiedInfo, array $rating = [])
    {
        $this->verifiedInfo = $verifiedInfo;
        $this->rating =  $rating;
    }

}

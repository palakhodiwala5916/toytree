<?php

namespace App\Entity\Sonata;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Sonata\UserBundle\Entity\BaseUser;

#[ORM\Table(name: 'sonata_user__user')]
#[ORM\Entity]
class SonataUserUser extends BaseUser
{
    /**
     * @var string
     */
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: 'string', length: 36)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    protected $id;

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }
}

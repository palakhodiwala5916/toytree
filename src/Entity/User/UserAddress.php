<?php

namespace App\Entity\User;

use App\Entity\AbstractEntity;
use App\Entity\Traits\UpdatedAtColumn;
use App\Repository\User\UserAddressRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'user_addresses')]
#[ORM\Entity(repositoryClass: UserAddressRepository::class)]
#[ORM\HasLifecycleCallbacks]
class UserAddress extends AbstractEntity
{
    use UpdatedAtColumn;

    #[ORM\ManyToOne(targetEntity: User::class, cascade: ['persist'], inversedBy: 'userAddresses')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: true, onDelete: 'CASCADE')]
    private ?User $user = null;

    #[ORM\Column(name:'address', type: Types::TEXT , nullable: false)]
    private ?string $address = null;

    // <editor-fold desc="Getters and Setters">

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    // </editor-fold>
}

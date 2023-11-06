<?php

namespace App\Entity\User;

use App\Entity\AbstractEntity;
use App\Repository\User\ConfirmationCodeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\Ignore;

#[ORM\Entity(repositoryClass: ConfirmationCodeRepository::class)]
#[ORM\Table(name: 'confirmation_codes')]
class ConfirmationCode extends AbstractEntity
{
    const ACTION_EMAIL_CONFIRMATION = 'EmailConfirmation';
    const ACTION_PASSWORD_RESET = 'PasswordReset';
    const ACTION_PHONE_CONFIRMATION = 'PhoneConfirmation';

    #[ORM\Column(name: 'code', type: 'string',length: 10, nullable: false)]
    #[Groups(['api'])]
    private ?string $code = null;

    #[ORM\Column(name: 'expire_at', type: Types::DATETIME_MUTABLE, nullable: false)]
    #[Ignore]
    private ?\DateTime $expireAt = null;

    #[ORM\ManyToOne(targetEntity: User::class, cascade: ['persist'], inversedBy: 'confirmationCodes')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: true, onDelete: 'CASCADE')]
    #[Ignore]
    private ?User $user = null;

    #[ORM\Column(name: 'action', type: 'string',length: 50, nullable: false)]
    #[Groups(['api'])]
    private ?string $action = null;

    public function __construct()
    {
        parent::__construct();
        $this->expireAt = \DateTimeService::now()->modify('+1min');
    }

    //<editor-fold desc="Getters & Setters" >
    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $users): self
    {
        $this->user = $users;

        return $this;
    }

    public function getAction(): ?string
    {
        return $this->action;
    }

    public function setAction(string $action): self
    {
        $this->action = $action;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getExpireAt(): ?\DateTime
    {
        return $this->expireAt;
    }

    /**
     * @param \DateTime|null $expireAt
     */
    public function setExpireAt(?\DateTime $expireAt): void
    {
        $this->expireAt = $expireAt;
    }

    //</editor-fold>
}

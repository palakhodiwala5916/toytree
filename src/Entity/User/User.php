<?php

namespace App\Entity\User;

use App\Entity\AbstractEntity;
use App\Entity\Traits\UpdatedAtColumn;
use App\Repository\User\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Ignore;

/**
 * @method string getUserIdentifier()
 */
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'users')]
#[UniqueEntity(fields: ['email'], message: "Sorry, this email address is already used.")]
#[UniqueEntity(fields: ['phoneNumber'], message: "Sorry, this Phone Number is already used.")]
#[ORM\HasLifecycleCallbacks]
class User extends AbstractEntity implements UserInterface, PasswordAuthenticatedUserInterface
{
    use UpdatedAtColumn;

    const ROLE_USER = 'ROLE_USER';

    #[ORM\Column(name:'full_name', type: 'string' ,length: 150, nullable: false)]
    #[Groups(['api', 'client:list', 'client:view'])]
    private string $fullName;

    #[ORM\Column(name:'email', type: 'string' ,length: 255, unique: true, nullable: true)]
    #[Groups(['api', 'client:list', 'client:view'])]
    private string $email;

    #[ORM\Column(name:'password', type: 'string' ,length: 255, nullable: true)]
    #[Ignore]
    private ?string $password = null;

    #[ORM\Column(name: 'salt', type: 'string', nullable: true)]
    #[Ignore]
    private ?string $salt = null;

    #[ORM\Column(name: 'phone_number', type: 'string', length: 15, unique: true, nullable: false)]
    #[Groups(['api', 'client:list', 'client:view'])]
    private string $phoneNumber;

    #[ORM\Column(name:'role', type: 'string', nullable: true)]
    #[Groups(['api', 'client:list', 'client:view'])]
    private string $role;

    #[ORM\Column(name:'profile_picture', type: 'string' ,length: 50, nullable: true)]
    #[Groups(['api', 'client:list', 'client:view'])]
    private ?string $profilePicture = null;

    #[ORM\Column(name:'apple_id', type: 'string' ,length: 100, nullable: true)]
    #[Groups(['api', 'client:list', 'client:view'])]
    private ?string $appleId = null;

    #[ORM\Column(name:'facebook_id', type: 'string' ,length: 100, nullable: true)]
    #[Groups(['api', 'client:list', 'client:view'])]
    private ?string $facebookId = null;

    #[ORM\Column(name:'google_id', type: 'string' ,length: 100, nullable: true)]
    #[Groups(['api', 'client:list', 'client:view'])]
    private ?string $googleId = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: ConfirmationCode::class, cascade: ['persist'])]
    #[Groups(['client:view'])]
    private Collection $confirmationCodes;

    #[ORM\Column(name: 'status', type: 'string', length: 25, enumType: UserStatus::class, options: ['default' => UserStatus::Active])]
    private ?UserStatus $status = null;

    public function __construct()
    {
        parent::__construct();
        $this->setStatus(UserStatus::Active);
    }

    // <editor-fold desc="Getters and Setters">

    public function getFullName(): string
    {
        return $this->fullName;
    }

    public function setFullName(string $full_name): self
    {
        $this->fullName = $full_name;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getProfilePicture(): ?string
    {
        return $this->profilePicture;
    }

    public function setProfilePicture(?string $profilePicture): self
    {
        $this->profilePicture = $profilePicture;

        return $this;
    }

    public function getAppleId(): ?string
    {
        return $this->appleId;
    }

    public function setAppleId(?string $appleId): self
    {
        $this->appleId = $appleId;

        return $this;
    }

    public function getFacebookId(): ?string
    {
        return $this->facebookId;
    }

    public function setFacebookId(?string $facebookId): self
    {
        $this->facebookId = $facebookId;

        return $this;
    }

    public function getGoogleId(): ?string
    {
        return $this->googleId;
    }

    public function setGoogleId(?string $googleId): self
    {
        $this->googleId = $googleId;

        return $this;
    }

    /**
     * @return Collection<int, ConfirmationCode>
     */
    public function getConfirmationCodes(): Collection
    {
        return $this->confirmationCodes;
    }

    public function addConfirmationCode(ConfirmationCode $confirmationCode): self
    {
        if (!$this->confirmationCodes->contains($confirmationCode)) {
            $this->confirmationCodes->add($confirmationCode);
            $confirmationCode->setUser($this);
        }

        return $this;
    }

    public function removeConfirmationCode(ConfirmationCode $confirmationCode): self
    {
        if ($this->confirmationCodes->removeElement($confirmationCode)) {
            // set the owning side to null (unless already changed)
            if ($confirmationCode->getUser() === $this) {
                $confirmationCode->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return UserStatus|null
     */
    public function getStatus(): ?UserStatus
    {
        return $this->status;
    }

    /**
     * @param UserStatus|null $status
     */
    public function setStatus(?UserStatus $status): void
    {
        $this->status = $status;
    }

    /**
     * @return string|null
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @param string $salt
     */
    public function setSalt(string $salt): void
    {
        $this->salt = $salt;
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUsername() : ?string
    {
        if ($this->email) {
            return $this->email;
        }

        return null;
    }

    public function __call(string $name, array $arguments)
    {
        // TODO: Implement @method string getUserIdentifier()
    }

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * @param string $role
     */
    public function setRole(string $role): void
    {
        $this->role = $role;
    }

    public function getRoles(): array
    {
        return [$this->role];
    }

    public function __toString(): string
    {
        return $this->fullName ?? $this->id;
    }
    // </editor-fold>
}

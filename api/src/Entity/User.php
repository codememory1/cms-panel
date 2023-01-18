<?php

namespace App\Entity;

use App\DBAL\Types\PasswordType;
use App\Entity\Interfaces\EntityInterface;
use App\Entity\Traits\IdentifierTrait;
use App\Entity\Traits\TimestampTrait;
use App\Enum\UserStatusEnum;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'users')]
#[ORM\HasLifecycleCallbacks]
class User implements EntityInterface
{
    use IdentifierTrait;
    use TimestampTrait;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column(length: 320)]
    private ?string $email = null;

    #[ORM\Column(type: PasswordType::NAME)]
    private ?string $password = null;

    #[ORM\Column(length: 50)]
    private ?string $status = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Role $role = null;

    public function __construct()
    {
        $this->generateUuid();
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
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

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function isActivated(): bool
    {
        return $this->getStatus() === UserStatusEnum::ACTIVATED->name;
    }

    public function isBlocked(): bool
    {
        return $this->getStatus() === UserStatusEnum::BLOCKED->name;
    }

    public function setStatus(UserStatusEnum $status): self
    {
        $this->status = $status->name;

        return $this;
    }

    public function getRole(): ?Role
    {
        return $this->role;
    }

    public function setRole(?Role $role): self
    {
        $this->role = $role;

        return $this;
    }
}

<?php

namespace App\Entity;

use App\DBAL\Types\PasswordType;
use App\Entity\Interfaces\EntityInterface;
use App\Entity\Interfaces\LogInterface;
use App\Entity\Traits\IdentifierTrait;
use App\Entity\Traits\LogTrait;
use App\Entity\Traits\TimestampTrait;
use App\Enum\UserStatusEnum;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'users')]
#[ORM\HasLifecycleCallbacks]
#[UniqueEntity('email', 'Пользователь с данной почтой уже существует')]
class User implements EntityInterface, LogInterface
{
    use IdentifierTrait;
    use TimestampTrait;
    use LogTrait;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column(length: 320)]
    private ?string $email = null;

    #[ORM\Column(type: PasswordType::NAME, nullable: true)]
    private ?string $password = null;

    #[ORM\Column(length: 50)]
    private ?string $status = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Role $role = null;

    #[ORM\OneToMany(mappedBy: 'executor', targetEntity: ActionLog::class, cascade: ['remove'])]
    private Collection $actionLogs;

    public function __construct()
    {
        $this->generateUuid();
        $this->actionLogs = new ArrayCollection();
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
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

    public function setStatus(?UserStatusEnum $status): self
    {
        $this->status = $status?->name;

        return $this;
    }

    public function getRole(): ?Role
    {
        return $this->role;
    }

    public function getRoleName(): ?string
    {
        return $this->getRole()?->getTitle();
    }

    public function setRole(?Role $role): self
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @return Collection<int, ActionLog>
     */
    public function getActionLogs(): Collection
    {
        return $this->actionLogs;
    }

    public function addActionLog(ActionLog $actionLog): self
    {
        if (!$this->actionLogs->contains($actionLog)) {
            $this->actionLogs->add($actionLog);
            $actionLog->setExecutor($this);
        }

        return $this;
    }

    public function removeActionLog(ActionLog $actionLog): self
    {
        if ($this->actionLogs->removeElement($actionLog)) {
            // set the owning side to null (unless already changed)
            if ($actionLog->getExecutor() === $this) {
                $actionLog->setExecutor(null);
            }
        }

        return $this;
    }
}

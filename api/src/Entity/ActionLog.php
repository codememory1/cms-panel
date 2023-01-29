<?php

namespace App\Entity;

use App\Entity\Interfaces\EntityInterface;
use App\Entity\Traits\CreatedAtTrait;
use App\Entity\Traits\IdentifierTrait;
use App\Repository\ActionLogRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActionLogRepository::class)]
#[ORM\Table('action_logs')]
#[ORM\HasLifecycleCallbacks]
class ActionLog implements EntityInterface
{
    use IdentifierTrait;
    use CreatedAtTrait;

    #[ORM\ManyToOne(inversedBy: 'actionLogs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $executor = null;

    #[ORM\Column(length: 255)]
    private ?string $entity = null;

    #[ORM\Column(length: 50)]
    private ?string $action = null;

    #[ORM\Column]
    private array $payload = [];

    public function __construct()
    {
        $this->generateUuid();
    }

    public function getExecutor(): ?User
    {
        return $this->executor;
    }

    public function setExecutor(?User $executor): self
    {
        $this->executor = $executor;

        return $this;
    }

    public function getEntity(): ?string
    {
        return $this->entity;
    }

    public function setEntity(string $entity): self
    {
        $this->entity = $entity;

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

    public function getPayload(): array
    {
        return $this->payload;
    }

    public function setPayload(array $payload): self
    {
        $this->payload = $payload;

        return $this;
    }
}

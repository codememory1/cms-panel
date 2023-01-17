<?php

namespace App\Entity\Traits;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

trait IdentifierTrait
{
    #[ORM\Id]
    #[ORM\Column(type: Types::STRING, length: 100, unique: true)]
    private ?string $id = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function generateUuid(): self
    {
        if (null === $this->getId()) {
            $this->id = Uuid::uuid4()->toString();
        }

        return $this;
    }
}
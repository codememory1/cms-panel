<?php

namespace App\Entity;

use App\Entity\Interfaces\EntityInterface;
use App\Entity\Traits\CreatedAtTrait;
use App\Entity\Traits\IdentifierTrait;
use App\Repository\TransactionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TransactionRepository::class)]
#[ORM\Table('transactions')]
#[ORM\HasLifecycleCallbacks]
class Transaction implements EntityInterface
{
    use IdentifierTrait;
    use CreatedAtTrait;

    #[ORM\ManyToOne(inversedBy: 'transactions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Phone $phone = null;

    #[ORM\Column(length: 255)]
    private ?string $hash = null;

    #[ORM\Column(length: 50)]
    private ?string $type = null;

    #[ORM\Column]
    private array $card = [];

    #[ORM\Column(length: 20)]
    private ?string $completedOnTime = null;

    #[ORM\Column(nullable: true)]
    private ?float $sum = null;

    public function __construct()
    {
        $this->generateUuid();
    }

    public function getPhone(): ?Phone
    {
        return $this->phone;
    }

    public function setPhone(?Phone $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function setHash(string $hash): self
    {
        $this->hash = $hash;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return array{type: string, last_number: integer}
     */
    public function getCard(): array
    {
        return $this->card;
    }

    /**
     * @param array{type: string, last_number: integer} $card
     */
    public function setCard(array $card): self
    {
        $this->card = $card;

        return $this;
    }

    public function getCompletedOnTime(): ?string
    {
        return $this->completedOnTime;
    }

    public function setCompletedOnTime(string $completedOnTime): self
    {
        $this->completedOnTime = $completedOnTime;

        return $this;
    }

    public function getSum(): ?float
    {
        return $this->sum;
    }

    public function setSum(?float $sum): self
    {
        $this->sum = $sum;

        return $this;
    }
}

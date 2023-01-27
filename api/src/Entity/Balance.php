<?php

namespace App\Entity;

use App\Entity\Interfaces\EntityInterface;
use App\Entity\Traits\IdentifierTrait;
use App\Entity\Traits\TimestampTrait;
use App\Repository\BalanceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BalanceRepository::class)]
#[ORM\Table('balances')]
#[ORM\HasLifecycleCallbacks]
class Balance implements EntityInterface
{
    use IdentifierTrait;
    use TimestampTrait;

    #[ORM\OneToOne(inversedBy: 'balance')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Phone $phone = null;

    #[ORM\Column]
    private float $balance = 0.0;

    public function __construct()
    {
        $this->generateUuid();
    }

    public function getPhone(): ?Phone
    {
        return $this->phone;
    }

    public function setPhone(Phone $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getBalance(): float
    {
        return $this->balance;
    }

    public function setBalance(float $balance): self
    {
        $this->balance = $balance;

        return $this;
    }
}

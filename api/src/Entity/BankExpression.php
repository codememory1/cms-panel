<?php

namespace App\Entity;

use App\Entity\Interfaces\EntityInterface;
use App\Entity\Interfaces\LogInterface;
use App\Entity\Traits\IdentifierTrait;
use App\Entity\Traits\LogTrait;
use App\Entity\Traits\TimestampTrait;
use App\Repository\BankExpressionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BankExpressionRepository::class)]
#[ORM\Table('bank_expressions')]
#[ORM\HasLifecycleCallbacks]
class BankExpression implements EntityInterface, LogInterface
{
    use IdentifierTrait;
    use TimestampTrait;
    use LogTrait;

    #[ORM\OneToOne(inversedBy: 'expression')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Bank $bank = null;

    #[ORM\Column(type: Types::JSON)]
    private array $transfer = [];

    #[ORM\Column(type: Types::JSON)]
    private array $enrollment = [];

    #[ORM\Column(type: Types::JSON)]
    private array $payment = [];

    #[ORM\Column(type: Types::JSON)]
    private array $purchase = [];

    public function __construct()
    {
        $this->generateUuid();
    }

    public function getBank(): ?Bank
    {
        return $this->bank;
    }

    public function setBank(?Bank $bank): self
    {
        $this->bank = $bank;

        return $this;
    }

    public function getTransfer(): array
    {
        return $this->transfer;
    }

    public function setTransfer(array $transfer): self
    {
        $this->transfer = $transfer;

        return $this;
    }

    public function getEnrollment(): array
    {
        return $this->enrollment;
    }

    public function setEnrollment(array $enrollment): self
    {
        $this->enrollment = $enrollment;

        return $this;
    }

    public function getPayment(): array
    {
        return $this->payment;
    }

    public function setPayment(array $payment): self
    {
        $this->payment = $payment;

        return $this;
    }

    public function getPurchase(): array
    {
        return $this->purchase;
    }

    public function setPurchase(array $purchase): self
    {
        $this->purchase = $purchase;

        return $this;
    }
}

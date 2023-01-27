<?php

namespace App\Entity;

use App\Entity\Interfaces\EntityInterface;
use App\Entity\Interfaces\LogInterface;
use App\Entity\Traits\IdentifierTrait;
use App\Entity\Traits\LogTrait;
use App\Entity\Traits\TimestampTrait;
use App\Repository\BankRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BankRepository::class)]
#[ORM\Table('banks')]
#[ORM\HasLifecycleCallbacks]
class Bank implements EntityInterface, LogInterface
{
    use IdentifierTrait;
    use TimestampTrait;
    use LogTrait;

    #[ORM\Column(length: 50)]
    private ?string $title = null;

    #[ORM\Column(unique: true)]
    private ?int $number = null;

    #[ORM\OneToOne(mappedBy: 'bank', cascade: ['persist', 'remove'])]
    private ?BankExpression $expression = null;

    public function __construct()
    {
        $this->generateUuid();
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getExpression(): ?BankExpression
    {
        return $this->expression;
    }

    public function setExpression(BankExpression $expression): self
    {
        // set the owning side of the relation if necessary
        if ($expression->getBank() !== $this) {
            $expression->setBank($this);
        }

        $this->expression = $expression;

        return $this;
    }
}

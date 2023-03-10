<?php

namespace App\Entity;

use App\DBAL\Types\PhoneType;
use App\Entity\Interfaces\EntityInterface;
use App\Entity\Interfaces\LogInterface;
use App\Entity\Traits\IdentifierTrait;
use App\Entity\Traits\LogTrait;
use App\Entity\Traits\TimestampTrait;
use App\Enum\PhoneStatusEnum;
use App\Repository\PhoneRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use libphonenumber\PhoneNumber;

#[ORM\Entity(repositoryClass: PhoneRepository::class)]
#[ORM\Table('phones')]
#[ORM\HasLifecycleCallbacks]
class Phone implements EntityInterface, LogInterface
{
    use IdentifierTrait;
    use TimestampTrait;
    use LogTrait;

    #[ORM\Column(type: PhoneType::NAME)]
    private null|string|PhoneNumber $number = null;

    #[ORM\Column(length: 50)]
    private ?string $status = null;

    #[ORM\OneToOne(mappedBy: 'phone', cascade: ['persist', 'remove'])]
    private ?Balance $balance = null;

    #[ORM\OneToMany(mappedBy: 'phone', targetEntity: Transaction::class, cascade: ['persist', 'remove'])]
    private Collection $transactions;

    public function __construct()
    {
        $this->generateUuid();
        $this->transactions = new ArrayCollection();
    }

    public function getNumber(): null|string|PhoneNumber
    {
        return $this->number;
    }

    public function setNumber(?string $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?PhoneStatusEnum $status): self
    {
        $this->status = $status?->name;

        return $this;
    }

    public function getBalance(): ?Balance
    {
        return $this->balance;
    }

    #[ORM\PrePersist]
    public function setBalance(): self
    {
        $balance = new Balance();

        $balance->setPhone($this);

        $this->balance = $balance;

        return $this;
    }

    /**
     * @return Collection<int, Transaction>
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    /**
     * @throws Exception
     */
    public function getSumEnrollment(): int
    {
        $sum = 0;
        $nowDate = (new DateTime())->format('Y-m-d');
        $transactions = $this->getTransactions()->filter(static function (Transaction $transaction) use ($nowDate) {
            return $transaction->getType() === 'enrollment' && $transaction->getCompletedOnTime()->format('Y-m-d') === $nowDate;
        });

        foreach ($transactions as $transaction) {
            $sum += $transaction->getSum();
        }

        return $sum;
    }

    /**
     * @throws Exception
     */
    public function getSumTransfer(): int
    {
        $sum = 0;
        $nowDate = (new DateTime())->format('Y-m-d');
        $transactions = $this->getTransactions()->filter(static function (Transaction $transaction) use ($nowDate) {
            return $transaction->getType() === 'transfer' && $transaction->getCompletedOnTime()->format('Y-m-d') === $nowDate;
        });

        foreach ($transactions as $transaction) {
            $sum += $transaction->getSum();
        }

        return $sum;
    }

    /**
     * @throws Exception
     */
    public function getSumPayment(): int
    {
        $sum = 0;
        $nowDate = (new DateTime())->format('Y-m-d');
        $transactions = $this->getTransactions()->filter(static function (Transaction $transaction) use ($nowDate) {
            return $transaction->getType() === 'payment' && $transaction->getCompletedOnTime()->format('Y-m-d') === $nowDate;
        });

        foreach ($transactions as $transaction) {
            $sum += $transaction->getSum();
        }

        return $sum;
    }

    public function getSumPurchase(): int
    {
        $sum = 0;
        $nowDate = (new DateTime())->format('Y-m-d');
        $transactions = $this->getTransactions()->filter(static function (Transaction $transaction) use ($nowDate) {
            return $transaction->getType() === 'purchase' && $transaction->getCompletedOnTime()->format('Y-m-d') === $nowDate;
        });

        foreach ($transactions as $transaction) {
            $sum += $transaction->getSum();
        }

        return $sum;
    }

    public function addTransaction(Transaction $transaction): self
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions->add($transaction);
            $transaction->setPhone($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): self
    {
        if ($this->transactions->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getPhone() === $this) {
                $transaction->setPhone(null);
            }
        }

        return $this;
    }
}

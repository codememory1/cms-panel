<?php

namespace App\Entity;

use App\DBAL\Types\PhoneType;
use App\Entity\Interfaces\EntityInterface;
use App\Entity\Interfaces\LogInterface;
use App\Entity\Traits\IdentifierTrait;
use App\Entity\Traits\LogTrait;
use App\Entity\Traits\TimestampTrait;
use App\Repository\PhoneRepository;
use Doctrine\ORM\Mapping as ORM;
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

    public function __construct()
    {
        $this->generateUuid();
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
}

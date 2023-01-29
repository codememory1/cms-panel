<?php

namespace App\Repository;

use App\Entity\Phone;
use App\Enum\PhoneStatusEnum;

/**
 * @template-extends AbstractRepository<Phone>
 */
final class PhoneRepository extends AbstractRepository
{
    protected ?string $alias = 'p';
    protected ?string $entity = Phone::class;

    public function getAllowed(): array
    {
        return $this->findBy([
            'status' => PhoneStatusEnum::ALLOWED->name
        ]);
    }
}

<?php

namespace App\Repository;

use App\Entity\Phone;

/**
 * @template-extends AbstractRepository<Phone>
 */
final class PhoneRepository extends AbstractRepository
{
    protected ?string $alias = 'p';
    protected ?string $entity = Phone::class;
}

<?php

namespace App\Repository;

use App\Entity\ActionLog;

/**
 * @template-extends AbstractRepository<ActionLog>
 */
final class ActionLogRepository extends AbstractRepository
{
    protected ?string $alias = 'al';
    protected ?string $entity = ActionLog::class;
}

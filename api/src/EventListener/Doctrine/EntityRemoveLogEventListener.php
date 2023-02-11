<?php

namespace App\EventListener\Doctrine;

use App\Entity\ActionLog;
use App\Entity\Interfaces\LogInterface;
use App\Service\AuthorizedUser;
use Doctrine\Persistence\Event\LifecycleEventArgs;

final class EntityRemoveLogEventListener
{
    public function __construct(
        private readonly AuthorizedUser $authorizedUser
    ) {
    }

    public function postRemove(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if ($entity instanceof LogInterface && $entity->trackActivities()) {
            $actionLog = new ActionLog();

            $actionLog->setExecutor($this->authorizedUser->getUser());
            $actionLog->setEntity($entity::class);
            $actionLog->setAction('DELETE');
            $actionLog->setPayload(['id' => $entity->getId()]);

            $args->getObjectManager()->persist($actionLog);
            $args->getObjectManager()->flush($actionLog);
        }
    }
}
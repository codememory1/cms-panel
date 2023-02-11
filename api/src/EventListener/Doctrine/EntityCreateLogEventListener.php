<?php

namespace App\EventListener\Doctrine;

use App\Entity\ActionLog;
use App\Entity\Interfaces\LogInterface;
use App\Service\AuthorizedUser;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\Event\LifecycleEventArgs;

final class EntityCreateLogEventListener
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly AuthorizedUser $authorizedUser
    ) {
    }

    public function postPersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if ($entity instanceof LogInterface && $entity->trackActivities()) {
            $actionLog = new ActionLog();

            $actionLog->setExecutor($this->authorizedUser->getUser());
            $actionLog->setEntity($entity::class);
            $actionLog->setAction('CREATE');
            $actionLog->setPayload(['_id' => $entity->getId()]);

            $this->em->persist($actionLog);
            $this->em->flush($actionLog);
        }
    }
}
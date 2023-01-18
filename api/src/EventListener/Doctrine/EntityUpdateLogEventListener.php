<?php

namespace App\EventListener\Doctrine;

use App\Entity\ActionLog;
use App\Entity\Interfaces\LogInterface;
use App\Service\AuthorizedUser;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\Event\LifecycleEventArgs;

final class EntityUpdateLogEventListener
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly AuthorizedUser $authorizedUser
    ) {
    }

    public function postUpdate(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if ($entity instanceof LogInterface && $entity->trackActivities()) {
            $changes = $this->em->getUnitOfWork()->getEntityChangeSet($entity);
            $payload = [
                '_id' => $entity->getId(),
                '_mutable_properties' => []
            ];

            foreach ($changes as $propertyName => $change) {
                if (!in_array($propertyName, $entity->ignoreProperties(), true)) {
                    $payload['_mutable_properties'][$propertyName] = [
                        'from' => $change[0],
                        'to' => $change[1]
                    ];
                }
            }

            $actionLog = new ActionLog();

            $actionLog->setExecutor($this->authorizedUser->getUser());
            $actionLog->setEntity($entity::class);
            $actionLog->setAction('UPDATE');
            $actionLog->setPayload($payload);

            $this->em->persist($actionLog);
        }

        $this->em->flush();
    }
}
<?php

namespace App\EventListener\Doctrine;

use App\Entity\ActionLog;
use App\Entity\Interfaces\LogInterface;
use App\Service\AuthorizedUser;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use libphonenumber\PhoneNumber;

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
                        'from' => $this->converterValue($change[0]),
                        'to' => $this->converterValue($change[1])
                    ];
                }
            }

            $actionLog = new ActionLog();

            $actionLog->setExecutor($this->authorizedUser->getUser());
            $actionLog->setEntity($entity::class);
            $actionLog->setAction('UPDATE');
            $actionLog->setPayload($payload);

            $this->em->persist($actionLog);
            $this->em->flush($actionLog);
        }
    }

    private function converterValue(mixed $value): mixed
    {
        if ($value instanceof PhoneNumber) {
            return "+{$value->getCountryCode()}{$value->getNationalNumber()}";
        }

        return $value;
    }
}
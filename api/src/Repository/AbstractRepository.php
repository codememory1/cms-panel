<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @template Entity as mixed
 */
abstract class AbstractRepository extends ServiceEntityRepository
{
    protected ?string $entity = null;
    protected ?string $alias = null;
    protected readonly QueryBuilder $qb;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, $this->entity);

        $this->qb = $this->createQueryBuilder($this->alias);
    }

    protected function getQueryBuilder(): QueryBuilder
    {
        return $this->qb;
    }

    /**
     * @return array<Entity>
     */
    protected function findByCriteria(array $criteria, array $orderBy = []): array
    {
        $qb = $this->getQueryBuilder();

        foreach ($orderBy as $propertyName => $as) {
            $qb->orderBy($propertyName, $as);
        }

        foreach ($criteria as $propertyName => $value) {
            $parameterName = str_replace('.', '_', $propertyName);

            $qb->andWhere("${propertyName} = :${parameterName}");
            $qb->setParameter($parameterName, $value);
        }

        return $qb->getQuery()->getResult() ?: [];
    }

    /**
     * @return array<Entity>
     */
    public function findAll(): array
    {
        return $this->findByCriteria([]);
    }

    /**
     * @return null|Entity
     */
    public function find(mixed $id, mixed $lockMode = null, mixed $lockVersion = null): mixed
    {
        return parent::find($id, $lockMode, $lockVersion);
    }

    /**
     * @return null|Entity
     */
    public function findOneBy(array $criteria, ?array $orderBy = null): ?object
    {
        return parent::findOneBy($criteria, $orderBy);
    }
}
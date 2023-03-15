<?php

namespace App\Infrastructure\Doctrine;

use App\Service\DataRepresentation\Pagination;
use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;
use LogicException;

final class Paginator
{
    private ?Query $query = null;
    private ?DoctrinePaginator $paginator = null;

    public function __construct(
        private readonly Pagination $paginationService
    ) {}

    public function setQuery(Query $query): self
    {
        $this->query = $query;

        return $this;
    }

    public function getPaginator(): DoctrinePaginator
    {
        if (null === $this->paginator) {
            if (null === $this->query) {
                throw new LogicException(sprintf('Paginator expects Query call setQuery method on %s', self::class));
            }

            $this->paginator = new DoctrinePaginator($this->query);
        }

        return $this->paginator;
    }

    public function getTotalPages(): int
    {
        return ceil($this->getPaginator()->count() / $this->getLimit());
    }

    public function getCurrentPage(): int
    {
        $pageFromQuery = $this->paginationService->get('page');

        if ($pageFromQuery < 1) {
            return 1;
        }

        if ($pageFromQuery > $this->getTotalPages()) {
            return $this->getTotalPages();
        }

        return (int) $pageFromQuery;
    }

    public function getOffsetFrom(): int
    {
        $offset = ($this->getCurrentPage() * $this->getLimit()) - $this->getLimit();

        return max($offset, 0);
    }

    public function getLimit(): int
    {
        $limitFromQuery = $this->paginationService->get('limit');

        if ($limitFromQuery < 1) {
            return 1;
        }

        if ($limitFromQuery > $max = 50) {
            return $max;
        }

        return (int) $limitFromQuery;
    }

    public function getData(): array
    {
        $query = $this->getPaginator()->getQuery();

        return $query
            ->setFirstResult($this->getOffsetFrom())
            ->setMaxResults($this->getLimit())
            ->getResult();
    }
}
<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class BaseRepository extends EntityRepository
{
    protected function getCountRows(
        QueryBuilder $qb,
        string $alias,
    ): int
    {
        $countQb = clone $qb;
        return (int) $countQb
            ->select('COUNT(' . $alias . ')')
            ->getQuery()
            ->setFirstResult(0)
            ->setMaxResults(null)
            ->getSingleScalarResult()
        ;
    }

    protected function getGroupCountRows(
        QueryBuilder $qb,
        string $field,
    ): int
    {
        $countQb = clone $qb;
        return (int) $countQb
            ->select('COUNT(DISTINCT ' . $field . ')')
            ->getQuery()
            ->setFirstResult(0)
            ->setMaxResults(null)
            ->getSingleScalarResult()
        ;
    }

    protected function addPagination(
        QueryBuilder $qb,
        ?int $page,
        ?int $itemsPerPage,
    ): void
    {
        if ($page && $itemsPerPage) {
            $qb
                ->setFirstResult(($page - 1) * $itemsPerPage)
                ->setMaxResults($itemsPerPage)
            ;
        }
    }

    protected function addOrder(
        QueryBuilder $qb,
        ?string $sortField,
        ?string $sortDir,
    ): void
    {
        if ($sortField && $sortDir) {
            $qb->orderBy($sortField, $sortDir);
        }
    }
}

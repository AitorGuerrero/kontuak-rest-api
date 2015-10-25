<?php

namespace KontuakBundle\Integration\Doctrine\PeriodicalMovement;

use Doctrine\ORM\QueryBuilder;
use Kontuak\PeriodicalMovement;
use Kontuak\PeriodicalMovement\Collection as BaseCollection;
use Kontuak\PeriodicalMovement\Id;
use KontuakBundle\Integration\Doctrine\IterableCollection;

class Collection implements BaseCollection
{
    use IterableCollection;

    public function __construct(QueryBuilder $queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
    }

    /**
     * @param Id $id
     * @return BaseCollection
     */
    public function byId(Id $id)
    {
        $this->queryBuilder
            ->andWhere('pm.doctrineId = :movementId')
            ->setParameter('movementId', $id->toString());

        return $this;
    }
}
<?php

namespace KontuakBundle\Integration\Doctrine\PeriodicalMovement;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\QueryBuilder;
use Kontuak\PeriodicalMovement;
use Kontuak\PeriodicalMovement\Collection as BaseCollection;
use KontuakBundle\Integration\Doctrine\IterableCollection;

class Collection implements BaseCollection
{
    use IterableCollection;

    public function __construct(QueryBuilder $queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
    }
}
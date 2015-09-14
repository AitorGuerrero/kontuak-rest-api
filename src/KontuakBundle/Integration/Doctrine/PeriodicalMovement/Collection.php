<?php

namespace KontuakBundle\Integration\Doctrine\PeriodicalMovement;

use Doctrine\ORM\QueryBuilder;
use Kontuak\PeriodicalMovement;
use Kontuak\PeriodicalMovement\Collection as BaseCollection;

class Collection implements BaseCollection
{

    /** @var \Doctrine\Common\Persistence\ObjectRepository */
    private $repository;
    /** @var Source */
    private $source;
    /** @var QueryBuilder */
    private $queryBuilder;

    public function __construct(Source $source)
    {
        $this->source = $source;
        $this->repository = $this->source->em()->getRepository('KontuakBundle:Integration\Doctrine\PeriodicalMovement');
        $this->queryBuilder = $this->repository->createQueryBuilder('pm');
    }

    /**
     * @param PeriodicalMovement\Id $id
     * @return PeriodicalMovement
     */
    public function find(PeriodicalMovement\Id $id)
    {
        return $this->repository->find($id->serialize());
    }

    /**
     * @return PeriodicalMovement[]
     */
    public function all()
    {
        return $this->queryBuilder->getQuery()->getArrayResult();
    }
}
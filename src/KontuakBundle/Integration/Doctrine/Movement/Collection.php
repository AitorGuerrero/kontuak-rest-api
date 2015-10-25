<?php

namespace KontuakBundle\Integration\Doctrine\Movement;

use Doctrine\ORM\QueryBuilder;
use Kontuak\Movement;
use Kontuak\Movement\Id;
use Kontuak\PeriodicalMovement;
use KontuakBundle\Integration\Doctrine\IterableCollection;

class Collection implements Movement\Collection
{

    use IterableCollection;

    public function __construct(QueryBuilder $queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
    }

    public function orderByDateDesc()
    {
        $this->queryBuilder->orderBy('m.date', 'DESC');
        return $this;
    }

    /**
     * @param \DateTimeInterface $date
     * @return Movement\Collection
     */
    public function filterDateLessThan(\DateTimeInterface $date)
    {
        $this->queryBuilder
            ->andWhere('m.date < :dateLessThan')
            ->setParameter('dateLessThan', $date);
        return $this;
    }

    /**
     * @param \DateTimeInterface $date
     * @return \Kontuak\Movement\Collection
     */
    public function filterDateLessOrEqualTo(\DateTimeInterface $date)
    {
        $this->queryBuilder
            ->andWhere('m.date <= :dateLessOrEqual')
            ->setParameter('dateLessOrEqual', $date);
        return $this;
    }

    /**
     * @return float
     * @todo insert the sum in the query
     */
    public function amountSum()
    {
        $totalAmount = 0;
        foreach($this->collection() as $movement) {
            $totalAmount += $movement->amount();
        }

        return $totalAmount;
    }

    /**
     * @param \DateTimeInterface $dateTime
     * @return Movement\Collection
     */
    public function filterByCreatedIsLessThan(\DateTimeInterface $dateTime)
    {
        $this->queryBuilder
            ->andWhere('m.created < :createdIsLessThan')
            ->setParameter('createdIsLessThan', $dateTime);
        return $this;
    }

    /**
     * @param \DateTimeInterface $date
     * @return Movement\Collection
     */
    public function filterByDateIs(\DateTimeInterface $date)
    {
        $this->queryBuilder
            ->andWhere('m.date = :dateEqualTo')
            ->setParameter('dateEqualTo', $date);
        return $this;
    }

    /**
     * @return Movement\Collection
     */
    public function orderByDate()
    {
        $this->queryBuilder->orderBy('m.date', 'ASC');
        return $this;
    }

    /**
     * @param PeriodicalMovement $periodicalMovement
     * @return Movement\Collection
     */
    public function filterByPeriodicalMovement(PeriodicalMovement $periodicalMovement)
    {
        $this->queryBuilder
            ->andWhere('m.periodicalMovement = :periodicalMovementIs')
            ->setParameter('periodicalMovementIs', $periodicalMovement);
        return $this;
    }

    /**
     * @param \DateTime $timeStamp
     * @return \Kontuak\Movement\Collection
     */
    public function filterByDateIsPostThan(\DateTime $timeStamp)
    {
        $this->queryBuilder
            ->andWhere('m.date > :dateIsPostThan')
            ->setParameter('dateIsPostThan', $timeStamp);

        return $this;
    }

    /**
     * @param Id $id
     * @return Movement\Collection
     */
    public function byId(Id $id)
    {
        $this->queryBuilder
            ->andWhere('m.doctrineId = :movementId')
            ->setParameter('movementId', $id->toString());

        return $this;
    }
}
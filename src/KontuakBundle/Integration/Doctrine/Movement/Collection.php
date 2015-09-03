<?php

namespace KontuakBundle\Integration\Doctrine\Movement;

use Doctrine\ORM\QueryBuilder;
use Kontuak\EntityId;
use Kontuak\Movement;
use Kontuak\MovementId;
use Kontuak\MovementsCollection as BaseCollection;
use Kontuak\PeriodicalMovement;

class Collection implements BaseCollection
{

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $source;

    private $repository;
    /** @var QueryBuilder */
    private $query;

    public function __construct(Source $source)
    {
        $this->source = $source;
        $this->repository = $this->source->getRepository('KontuakBundle:Integration\Doctrine\Movement');
        $this->query = $this->repository->createQueryBuilder('m');
    }

    public function orderByDateDesc()
    {
        $this->query->orderBy('date', 'DESC');
    }

    /**
     * @param int $amount
     * @return BaseCollection
     */
    public function limit($amount)
    {
        $this->query->setMaxResults($amount);
    }

    /**
     * @param \DateTimeInterface $date
     * @return \Kontuak\MovementsCollection
     */
    public function filterDateLessThan(\DateTimeInterface $date)
    {
        $this->query
            ->where('m.date < :dateLessThan')
            ->setParameter('dateLessThan', $date);
    }

    /**
     * float
     */
    public function amountSum()
    {
        // TODO: Implement amountSum() method.
    }

    /**
     * @param \DateTimeInterface $dateTime
     * @return \Kontuak\MovementsCollection
     */
    public function filterByCreatedIsLessThan(\DateTimeInterface $dateTime)
    {
        $this->query
            ->where('m.created < :createdIsLessThan')
            ->setParameter('createdIsLessThan', $dateTime);
    }

    /**
     * @param \DateTimeInterface $date
     * @return \Kontuak\MovementsCollection
     */
    public function filterByDateIs(\DateTimeInterface $date)
    {
        $this->query
            ->where('m.date = :dateEqualTo')
            ->setParameter('dateEqualTo', $date);
    }

    /**
     * @return BaseCollection
     */
    public function orderByDate()
    {
        $this->query->orderBy('date', 'ASC');
    }

    /**
     * @return Movement[]
     */
    public function toArray()
    {
        return $this->query->getResult();
    }

    /**
     * @param MovementId $id
     * @return BaseCollection
     */
    public function filterById(MovementId $id)
    {
    }

    /**
     * @param PeriodicalMovement $periodicalMovement
     * @return BaseCollection
     */
    public function filterByPeriodicalMovement(PeriodicalMovement $periodicalMovement)
    {
        $this->query
            ->where('m.periodicalMovementId = :periodicalMovement')
            ->set('periodicalMovement', $periodicalMovement->id()->serialize());
    }

    /**
     * @return Movement
     */
    public function first()
    {
        return $this->query->getFirstResult();
    }
}
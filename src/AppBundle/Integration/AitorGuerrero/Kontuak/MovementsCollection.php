<?php

namespace AppBundle\Integration\AitorGuerrero\Kontuak;

use Doctrine\Common\Persistence\ObjectManager;
use Kontuak\EntityId;
use Kontuak\Movement;
use Kontuak\MovementsCollection as BaseCollection;

class MovementsCollection implements BaseCollection
{

    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(ObjectManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param Movement $movement
     * @return void
     */
    public function add(Movement $movement)
    {
        $this->entityManager->persist($movement);
    }

    /**
     * @param EntityId $id
     * @return Movement
     */
    public function find(EntityId $id)
    {
        return $this
            ->entityManager
            ->getRepository('AppBundle:Movement')
            ->find($id->serialize());
    }

    public function orderByDateDesc()
    {
        // TODO: Implement orderByDateDesc() method.
    }

    /**
     * @param int $amount
     */
    public function limit($amount)
    {
        // TODO: Implement limit() method.
    }

    /**
     * @return Movement[]
     */
    public function all()
    {
        // TODO: Implement all() method.
    }

    /**
     * @param \DateTimeInterface $date
     * @return \Kontuak\MovementsCollection
     */
    public function filterDateLessThan(\DateTimeInterface $date)
    {
        // TODO: Implement filterDateLessThan() method.
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
        // TODO: Implement filterByCreatedIsLessThan() method.
    }

    /**
     * @param \DateTimeInterface $date
     * @return \Kontuak\MovementsCollection
     */
    public function filterByDateIs(\DateTimeInterface $date)
    {
        // TODO: Implement filterByDateIs() method.
    }
}
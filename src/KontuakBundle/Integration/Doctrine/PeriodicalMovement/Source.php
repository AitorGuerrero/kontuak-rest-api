<?php

namespace KontuakBundle\Integration\Doctrine\PeriodicalMovement;

use Doctrine\Common\Persistence\ObjectManager;
use Kontuak\PeriodicalMovement;
use Kontuak\PeriodicalMovement\Id;
use Kontuak\PeriodicalMovement\Source as BaseSource;

class Source implements BaseSource
{

    /** @var ObjectManager */
    private $em;
    /** @var \Doctrine\Common\Persistence\ObjectRepository */
    private $repository;

    public function __construct(ObjectManager $entityManager)
    {
        $this->em = $entityManager;
        $this->repository = $this->em->getRepository('KontuakBundle:Integration\Doctrine\PeriodicalMovement');
    }

    /**
     * @return \Kontuak\PeriodicalMovement\Collection
     */
    public function collection()
    {
        return new Collection($this->repository->createQueryBuilder('pm'));
    }

    public function add(PeriodicalMovement $movement)
    {
        $this->em->persist($movement);
    }

    /**
     * @param Id $id
     * @return PeriodicalMovement
     */
    public function get(Id $id)
    {
        return $this->repository->find($id->serialize());
    }
}
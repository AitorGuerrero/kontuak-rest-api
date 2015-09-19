<?php

namespace KontuakBundle\Integration\Doctrine\PeriodicalMovement;

use Doctrine\Common\Persistence\ObjectManager;
use Kontuak\PeriodicalMovement;
use Kontuak\PeriodicalMovement\Source as BaseSource;

class Source implements BaseSource
{

    /** @var ObjectManager */
    private $em;

    public function __construct(ObjectManager $entityManager)
    {

        $this->em = $entityManager;
    }

    /**
     * @return \Kontuak\PeriodicalMovement\Collection
     */
    public function collection()
    {
        return new Collection($this);
    }

    public function add(PeriodicalMovement $movement)
    {
        $this->em->persist($movement);
    }

    public function toArray()
    {
        // TODO: Implement toArray() method.
    }

    public function em()
    {
        return $this->em;
    }
}
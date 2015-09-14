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
        $periodicalMovement = new \KontuakBundle\Integration\Doctrine\PeriodicalMovement(
            $movement->id(),
            $movement->amount(),
            $movement->concept(),
            $movement->starts(),
            $movement->period()
        );
        $this->em->persist($periodicalMovement);
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
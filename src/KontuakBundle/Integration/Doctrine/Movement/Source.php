<?php

namespace KontuakBundle\Integration\Doctrine\Movement;

use Doctrine\ORM\EntityManagerInterface;
use Kontuak\Movement;

class Source implements Movement\Source
{
    /** @var EntityManagerInterface */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @return Movement\Collection
     */
    public function collection()
    {
        return new Collection($this);
    }

    /**
     * @param Movement $movement
     */
    public function add(Movement $movement)
    {
        $doctrineMovement = new \KontuakBundle\Integration\Doctrine\Movement(
            $movement->id(),
            $movement->amount(),
            $movement->concept(),
            $movement->date(),
            $movement->created()
        );
        $this->em->persist($doctrineMovement);
    }

    public function em() {
        return $this->em;
    }

    /**
     * @param Movement $movement
     * @return void
     */
    public function remove(Movement $movement)
    {
        $this->em->remove($movement);
    }

    public function persist(Movement $movement)
    {
        $this->em->persist($movement);
    }
}
<?php

namespace KontuakBundle\Integration\Doctrine\Movement;

use Doctrine\ORM\EntityManagerInterface;
use Kontuak\Movement;
use Kontuak\MovementsCollection;
use Kontuak\MovementsSource;

class Source implements MovementsSource
{
    /** @var EntityManagerInterface */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @return MovementsCollection
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
        $this->em->persist($movement);
    }
}
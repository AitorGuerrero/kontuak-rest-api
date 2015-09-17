<?php

namespace KontuakBundle\Integration\Doctrine\Movement;

use Kontuak\Movement\Factory as FactoryInterface;
use Kontuak\Movement\Id;
use KontuakBundle\Integration\Doctrine\Movement;

class Factory implements FactoryInterface
{

    /**
     * @param Id $movementId
     * @param $amount
     * @param $concept
     * @param \DateTime $date
     * @param \DateTime $created
     * @return \Kontuak\Movement
     */
    public function make(Id $movementId, $amount, $concept, \DateTime $date, \DateTime $created)
    {
        return new Movement($movementId, $amount, $concept, $date, $created);
    }
}
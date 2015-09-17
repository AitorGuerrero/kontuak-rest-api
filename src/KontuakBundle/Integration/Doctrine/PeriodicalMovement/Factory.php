<?php

namespace KontuakBundle\Integration\Doctrine\PeriodicalMovement;

use Kontuak\Period;
use Kontuak\PeriodicalMovement;
use Kontuak\PeriodicalMovement\Factory as FactoryInterface;

class Factory implements FactoryInterface
{

    public function make(
        PeriodicalMovement\Id $id,
        $amount,
        $concept,
        \DateTime $starts,
        Period $period
    )
    {
        return new \KontuakBundle\Integration\Doctrine\PeriodicalMovement(
            $id,
            $amount,
            $concept,
            $starts,
            $period
        );
    }
}
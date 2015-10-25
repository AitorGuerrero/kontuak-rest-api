<?php

namespace KontuakBundle\Integration\Doctrine;

use Kontuak\Movement\Id;

class Movement extends \Kontuak\Movement
{
    /** @var string */
    protected $doctrineId;

    public static function fromMovement(\Kontuak\Movement $movement)
    {
        return new self(
            $movement->id(),
            $movement->amount(),
            $movement->concept(),
            $movement->date(),
            $movement->created()
        );
    }

    public function mapToDoctrine()
    {
        $this->doctrineId = $this->id()->toString();
    }

    public function mapToDomain()
    {
        $this->id = Id::parse($this->doctrineId);
    }
}
<?php

namespace KontuakBundle\Integration\Doctrine;

use Kontuak\Movement as BaseMovement;
use Kontuak\MovementId;

class Movement extends BaseMovement
{
    /** @var string */
    protected $doctrineId;

    public function mapToDoctrine()
    {
        $this->doctrineId = $this->id()->serialize();
    }

    public function mapToDomain()
    {
        $this->id = MovementId::fromString($this->doctrineId);
    }
}
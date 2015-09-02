<?php

namespace KontuakBundle\Integration\Doctrine;

use Kontuak\Movement as BaseMovement;

class Movement extends BaseMovement
{
    /** @var string */
    protected $doctrineId;

    public function mapToDoctrine()
    {
        $this->doctrineId = $this->id()->serialize();
    }
}
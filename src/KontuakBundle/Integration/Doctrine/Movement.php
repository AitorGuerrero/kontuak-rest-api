<?php

namespace KontuakBundle\Integration\Doctrine;

use Kontuak\Movement\Id;

class Movement extends \Kontuak\Movement
{
    /** @var string */
    protected $doctrineId;

    public function mapToDoctrine()
    {
        $this->doctrineId = $this->id()->serialize();
    }

    public function mapToDomain()
    {
        $this->id = Id::fromString($this->doctrineId);
    }
}
<?php

namespace KontuakBundle\Integration\Doctrine;

use Kontuak\Movement\Id;

class Movement extends \Kontuak\Movement
{
    /** @var string */
    protected $doctrineId;

    public function __construct(
        Id $movementId,
        $amount,
        $concept,
        \DateTime $date,
        \DateTime $created
    ) {
        parent::__construct($movementId, $amount, $concept, $date, $created);
    }

    public function mapToDoctrine()
    {
        $this->doctrineId = $this->id()->serialize();
    }

    public function mapToDomain()
    {
        $this->id = new Id($this->doctrineId);
    }
}
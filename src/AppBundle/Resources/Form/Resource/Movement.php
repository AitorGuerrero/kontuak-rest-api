<?php

namespace AppBundle\Resources\Form\Resource;

use Symfony\Component\Validator\Constraints as Assert;

class Movement 
{
    public function __construct(\Kontuak\Ports\Resource\Movement $movement = null)
    {
        if (!is_null($movement)) {
            $this->id = $movement->id();
            $this->amount = $movement->amount();
            $this->concept = $movement->concept();
            $this->date = $movement->date()->format('Y-m-d');
        }
    }

    /**
     * @Assert\Uuid
     * @var string
     */
    public $id;
    /**
     * @Assert\NotBlank()
     * @var float
     */
    public $amount;
    /**
     * @Assert\NotBlank()
     * @var string
     */
    public $concept;
    /**
     * @Assert\Date()
     * @Assert\NotBlank()
     * @var \DateTime
     */
    public $date;
}
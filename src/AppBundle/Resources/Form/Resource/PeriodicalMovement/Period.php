<?php

namespace AppBundle\Resources\Form\Resource\PeriodicalMovement;

use Symfony\Component\Validator\Constraints as Assert;

class Period 
{
    const TYPE_DAYS = 'days';
    const TYPE_MONTHS = 'months';
    /**
     * @Assert\NotBlank()
     * @var string
     */
    public $type;
    /**
     * @Assert\NotBlank()
     * @var int
     */
    public $amount;
}
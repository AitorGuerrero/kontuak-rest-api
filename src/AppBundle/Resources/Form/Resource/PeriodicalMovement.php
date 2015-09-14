<?php

namespace AppBundle\Resources\Form\Resource;

use Symfony\Component\Validator\Constraints as Assert;

class PeriodicalMovement 
{
    /**
     * @Assert\Uuid
     * @Assert\NotBlank()
     * @var string
     */
    public $id;

    /**
     * @Assert\NotBlank()
     * @var float
     */
    public $concept;

    /**
     * @Assert\NotBlank()
     * @var float
     */
    public $amount;

    /**
     * @Assert\Date()
     * @Assert\NotBlank()
     * @var \DateTime
     */
    public $starts;

    /**
     * @Assert\Type(type="AppBundle\Resources\Form\Resource\PeriodicalMovement\Period")
     * @Assert\Valid()
     * @var \AppBundle\Resources\Form\Resource\PeriodicalMovement\Period
     */
    public $period;
}
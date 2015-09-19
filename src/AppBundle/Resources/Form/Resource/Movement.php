<?php

namespace AppBundle\Resources\Form\Resource;

use Symfony\Component\Validator\Constraints as Assert;

class Movement 
{
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
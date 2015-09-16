<?php

namespace AppBundle\Resources\Form\Type;

use AppBundle\Resources\Form\Type\PeriodicalMovement\Period;
use Symfony\Component\Form\FormBuilderInterface;

class NewMovement extends Movement
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->remove('id')
            ->add('period', new Period())
        ;
    }
}
<?php

namespace AppBundle\Resources\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;

class CompleteMovement extends Movement
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('periodical_movement', new PeriodicalMovement())
        ;
    }
}
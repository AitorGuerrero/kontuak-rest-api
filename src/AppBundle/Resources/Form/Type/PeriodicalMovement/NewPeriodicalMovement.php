<?php

namespace AppBundle\Resources\Form\Type\PeriodicalMovement;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class NewPeriodicalMovement extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $periodType = new Period();
        $builder
            ->add('id', 'text')
            ->add('amount', 'number')
            ->add('concept', 'text')
            ->add('starts', 'date')
            ->add($periodType->getName(), $periodType);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => false,
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'new_periodical_movement';
    }
}
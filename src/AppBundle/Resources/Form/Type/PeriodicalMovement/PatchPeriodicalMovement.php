<?php

namespace AppBundle\Resources\Form\Type\PeriodicalMovement;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PatchPeriodicalMovement extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $periodType = new Period();
        $builder
            ->add('amount', 'number', ['required' => false])
            ->add('concept', 'text', ['required' => false])
            ->add('starts', 'date', ['required' => false])
            ->add($periodType->getName(), $periodType, ['required' => false]);
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
        return 'update_periodical_movement';
    }
}
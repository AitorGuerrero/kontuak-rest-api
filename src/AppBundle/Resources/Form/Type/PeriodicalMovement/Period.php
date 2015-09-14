<?php

namespace AppBundle\Resources\Form\Type\PeriodicalMovement;

use AppBundle\Resources\Form\Resource\PeriodicalMovement\Period as PeriodResource;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class Period extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', 'choice', [
                'choices' => [PeriodResource::TYPE_DAYS => 'Days', PeriodResource::TYPE_MONTHS => 'Months']
            ])
            ->add('amount', 'number');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Resources\Form\Resource\PeriodicalMovement\Period',
            'csrf_protection' => false,
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'period';
    }
}
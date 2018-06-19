<?php

namespace BusinessModelBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class ReservationType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('activite','entity', array(
'class'
=> 'BusinessModelBundle:Activite',
'property' => 'libelle',
'multiple' => false))
            ->add('utilisateurs', 'entity', array(
'class' => 'BusinessModelBundle:User',
'property' => 'nomComplet',
'multiple' => true))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BusinessModelBundle\Entity\Reservation'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'businessmodelbundle_reservation';
    }
}

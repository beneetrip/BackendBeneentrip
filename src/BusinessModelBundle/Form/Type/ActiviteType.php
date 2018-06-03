<?php

namespace BusinessModelBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ActiviteType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle')
            ->add('lieuDestination')
            ->add('date')
            ->add('heure')
            ->add('nbParticipants')
            ->add('prixIndividu')
            ->add('description')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BusinessModelBundle\Entity\Activite'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'businessmodelbundle_activite';
    }
}

<?php

namespace BusinessModelBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceList;

class DiscussionType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('message')
            ->add('activite','entity', array(
				'class'
				=> 'BusinessModelBundle:Activite',
				'property' => 'libelle',
				'multiple' => false))
				->add('auteur', 'entity', array(
				'class' => 'BusinessModelBundle:User',
				'property' => 'nomComplet',
				'multiple' => false))
            ->add('destinataires', 'entity', array(
				'class' => 'BusinessModelBundle:User',
				'property' => 'nomComplet',
				'multiple' => true,
				'required' =>false))
				->add('type', 'choice', array(
       'choice_list' => new ChoiceList(
        array('Discussion', 'Report'),
        array('Discussion', 'Report'))
      ))
      ->add('titre');
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BusinessModelBundle\Entity\Discussion'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'businessmodelbundle_discussion';
    }
}

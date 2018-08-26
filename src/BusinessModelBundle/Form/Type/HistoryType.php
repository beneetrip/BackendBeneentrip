<?php

namespace BusinessModelBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceList;

class HistoryType extends AbstractType
{

	private $listeChoix;	
	private $listeChoix2;	
	
	public function __construct($listeChoix,$listeChoix2)
	{
    $this->listeChoix = $listeChoix; 
    $this->listeChoix2 = $listeChoix2; 
	}
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	
        $builder
				->add('utilisateurs','entity', array(
				'class'
				=> 'BusinessModelBundle:User',
				'property' => 'nomComplet',
				'multiple' => false))
				->add('type', 'choice', array(
       'choice_list' => new ChoiceList(
        $this->listeChoix,
        $this->listeChoix)
      ))
      ->add('evenement', 'choice', array(
       'choice_list' => new ChoiceList(
        $this->listeChoix2,
        $this->listeChoix2)
      ))
             ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'empty_data' => null
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'businessmodelbundle_history';
    }
   
	
}

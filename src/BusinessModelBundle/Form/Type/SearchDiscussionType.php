<?php

namespace BusinessModelBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceList;

class SearchDiscussionType extends AbstractType
{


	private $listeChoix;	
	
	public function __construct($listeChoix)
	{
    $this->listeChoix = $listeChoix; 
	}
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	
        $builder
				->add('activite', 'text', array('required' =>false))
      		->add('auteur', 'text', array('required' =>false))
      		->add('destinataires', 'text', array('required' =>false))
            ->add('type', 'choice', array(
       'choice_list' => new ChoiceList(
        $this->listeChoix,
        $this->listeChoix)
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
        return 'businessmodelbundle_searchdiscussion';
    }
   
	
}

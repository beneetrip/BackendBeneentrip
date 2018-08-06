<?php

namespace BusinessModelBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\CallbackValidator;
use Symfony\Component\Form\FormError;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceList;


class SearchActiviteType extends AbstractType
{
	
	private $listeDestinations;
	
	public function __construct($listeDestinations)
	{
    $this->listeDestinations = $listeDestinations;
	}
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	
        $builder
            /*->add('lieuDestinations', 'choice', array(
       'choice_list' => new ChoiceList(
        $this->listeDestinations,
        $this->listeDestinations)
      ))
      ->add('categorie','entity', array(
				'class'
				=> 'BusinessModelBundle:Categorie',
				'property' => 'nom',
				'multiple' => false))
				->add('auteur','entity', array(
				'class'
				=> 'BusinessModelBundle:User',
				'property' => 'nomComplet',
				'multiple' => false))*/
				->add('lieuDestinations', 'text', array('required' =>false))
      		->add('categorie', 'text', array('required' =>false))
				->add('auteur', 'text', array('required' =>false))
            ->add('dateDebut', 'datetime', array('widget' =>'single_text','format' =>'MM/dd/yyyy','required' =>false))
            ->add('dateFin', 'datetime', array('widget' =>'single_text','format' =>'MM/dd/yyyy','required' =>false))
            ->add('heureDebut', 'datetime', array('widget' =>'single_text','format' =>'HH:mm','required' =>false))
            ->add('heureFin', 'datetime', array('widget' =>'single_text','format' =>'HH:mm','required' =>false))
            ->add('prixIndividuMin', 'number', array('required' =>false))
            ->add('prixIndividuMax', 'number', array('required' =>false))
            ->add('nbParticipantsMin', 'integer', array('required' =>false))
            ->add('nbParticipantsMax', 'integer', array('required' =>false))
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
        return 'businessmodelbundle_searchactivite';
    }
   
	
}

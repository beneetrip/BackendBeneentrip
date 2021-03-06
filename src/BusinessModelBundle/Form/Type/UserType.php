<?php

namespace BusinessModelBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceList;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends BaseType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        // add your custom field
        $builder->add('genre', 'choice', array(
       'choice_list' => new ChoiceList(
        array('Homme', 'Femme'),
        array('Homme', 'Femme'))
      ));
        
        $builder->add('nom');
        $builder->add('prenom');
        $builder->add('dateNaissance','birthday', array('widget' =>'single_text','format' =>'MM/dd/yyyy','required' =>false));
        $builder->add('telephone');
        $builder->add('typeUtilisateur', 'choice', array(
       'choice_list' => new ChoiceList(
        array('Touriste','Guide'),
        array('Touriste','Guide'))
      ));
      $builder->add('privilege', 'choice', array(
       'choice_list' => new ChoiceList(
        array('ROLE_USER','ROLE_ADMIN'),
        array('USER','ADMIN'))
      ));
        $builder->add('fichierPhoto','file',array('required' =>false));
        $builder->add('enabled');
        $builder->add('langues', 'entity', array(
				'class' => 'BusinessModelBundle:Langue',
				'property' => 'nom',
				'multiple' => true));
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BusinessModelBundle\Entity\User'
        ));
    }

    public function getName()
    {
        return 'businessmodelbundle_user';
    }

    
}

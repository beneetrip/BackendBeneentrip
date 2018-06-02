<?php

namespace AdminBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceList;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

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
        
        $builder->add('nomComplet');
        $builder->add('age');
        $builder->add('typeUtilisateur', 'choice', array(
       'choice_list' => new ChoiceList(
        array('Administrateur', 'Touriste','Guide'),
        array('Administrateur','Touriste','Guide'))
      ));
        $builder->add('fichierPhoto','file',array('required' =>false));
        //$builder->add('photo','text', array('required' =>false));
    }

    public function getName()
    {
        return 'adminbundle_user';
    }

    
}
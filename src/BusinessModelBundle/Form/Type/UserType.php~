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
        
        $builder->add('nomComplet');
        $builder->add('age');
        $builder->add('typeUtilisateur', 'choice', array(
       'choice_list' => new ChoiceList(
        array('Touriste','Guide'),
        array('Touriste','Guide'))
      ));
      $builder->add('privilege', 'choice', array(
       'choice_list' => new ChoiceList(
        array('ROLE_USER','ROLE_ADMIN','ROLE_SUPERADMIN'),
        array('USER','ADMIN','SUPERADMIN'))
      ));
        $builder->add('fichierPhoto','file',array('required' =>false));
        $builder->add('enabled');
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

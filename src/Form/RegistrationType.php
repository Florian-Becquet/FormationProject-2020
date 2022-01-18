<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('login', null, array('label' => false))
            ->add('password', PasswordType::class, array('label' => false))
            ->add('confirm_password', PasswordType::class, array('label' => false))
            ->add('nom', null, array('label' => false))
            ->add('prenom', null, array('label' => false))
            ->add('mail', null, array('label' => false))
            ->add('ville', null, array('label' => false))
            ->add('tel', TelType::class, array('label' => false))
            ->add('type', ChoiceType::class, 
                array(
                'choices' => array('Particulier' => 'Particulier', 'Coach' => 'Coach', 'Association' => 'Association'),
                'expanded' => true,
            ))
            ->add('sexe', ChoiceType::class, 
                array(
                'choices' => array('homme' => 'homme', 'femme' => 'femme'),
                'expanded' => true,
            ))
            ->add('dateNaiss', DateType::class, array(
                'years' => range(date('1940'), date('Y')),
                'label' => "Date de naissance",
            ))
            ->add('nomSociete', null, array('label' => false))
            ->add('nomAssoc', null, array('label' => false))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}

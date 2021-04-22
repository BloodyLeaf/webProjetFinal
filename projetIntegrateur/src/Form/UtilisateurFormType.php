<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class UtilisateurFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', TextType::class, ['label' => false])
            ->add('nom', TextType::class, ['label' => false])
            ->add('prenom', TextType::class, ['label' => false])
            ->add('noGroupe', TextType::class, ['label' => false])
            ->add('roles',ChoiceType::class,
                array('choices' => array(
                'Admin' => 'ROLE_ADMIN',
                'User' => 'ROLE_USER'),
                'multiple'=>true,
                 'label' => false))
            ->add('etat',ChoiceType::class,
                array('choices' => array(
                'Actif' => '1',
                'Inactif' => '0'),
                'multiple'=>false
                , 'label' => false))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}

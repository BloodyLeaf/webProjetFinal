<?php
/****************************************
   Fichier : UtilisateurFormType.php
   Auteur : Olivier Vigneault
   Fonctionnalité : À faire
   Date : 19 avril 2021
   Vérification :
   Date           	Nom               	Approuvé
   =========================================================
   25 avril 2021

   Historique de modifications :
   Date           	Nom               	Description
   =============================================
   
    ****************************************/
namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class UtilisateurFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', TextType::class, ['label' => false])
            ->add('nom', TextType::class, ['label' => false, 'constraints' => [new Regex("[^0-9]", "Votre nom peut seulement contenir des lettres.")]])
            ->add('prenom', TextType::class, ['label' => false, 'constraints' => [new Regex("[^0-9]", "Votre prenom peut seulement contenir des lettres.")]])
            ->add('noGroupe', NumberType::class, ['label' => false])
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

<?php
/****************************************
   Fichier : modifyPieceType.php
   Auteur : Samuel Fournier, Olivier Vigneault, William Goupil, Pier-Alexander Caron
   Fonctionnalité : À faire
   Date : 19 avril 2021
   Vérification :
   Date           	Nom               	Approuvé
    25 avril 2021    Approuvé par l'équipe
   =========================================================
   Historique de modifications :
   Date           	Nom               	Description
   =========================================================
    22 avril 2021 / William / création du form et ajout des champs
 ****************************************/


namespace App\Form;

use App\Entity\Piece;
use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Validator\Constraints\PositiveOrZero;

class ModifyPieceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, array('label' => 'Nom :'))
            ->add('description', TextType::class, array('label' => 'Description :'))
            ->add('QteTotal', IntegerType::class, array('label' => 'Quantite total :','constraints'=> [new PositiveOrZero()]))
            ->add('QteEmprunter',  IntegerType::class, array('label' => 'Quantite emprunter :','constraints'=> [new PositiveOrZero()]))
            ->add('QteBrise',  IntegerType::class, array('label' => 'Quantite brise :','constraints'=> [new PositiveOrZero()]))
            ->add('QtePerdu',  IntegerType::class, array('label' => 'Quantite perdu :','constraints'=> [new PositiveOrZero()]))
            ->add('idCategorie',EntityType::class,
                array
                (
                    'class' => Categorie::class,
                    'choice_label' => 'nom',
                    'label' => 'Selection de la catégorie', 
                    'multiple' => false,
                    'required' => true
                )
            );
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Piece::class,
        ]);
    }
}

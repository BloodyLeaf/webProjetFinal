<?php

namespace App\Form;

use App\Entity\Piece;
use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class AddPieceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, array('label' => 'Nom :'))
            ->add('description', TextType::class, array('label' => 'Description :'))
            ->add('QteTotal', IntegerType::class, array('label' => 'Quantite total :'))
            
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
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Piece::class,
        ]);
    }
}

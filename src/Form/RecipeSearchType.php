<?php

namespace App\Form;

use App\Entity\Recipe;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecipeSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', SearchType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Entrez un ou plusieurs mots-clés'
                ]
            ])
//            ->add('category', ChoiceType::class, [
//                'label' => false,
//                'required' => false,
//                'attr' => [
//                    'class' => 'form-control',
//                ],
//                'choices' => [
//                    'Choisissez une catégorie' => [
//                        'Entrée' => 'entrée',
//                        'Plat' => 'plat',
//                        'Dessert' => 'dessert',
//                        'Cocktail & boisson' => 'cocktail & boisson',
//                        'Sauce' => 'sauce',
//                    ]
//                ]
//            ])
            ->add('Rechercher', SubmitType::class, [
                'attr' => [
                    'class' => 'btn-primary'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class
        ]);
    }
}

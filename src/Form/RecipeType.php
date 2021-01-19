<?php

namespace App\Form;

use App\Entity\Recipe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                "label" => "Titre :"
            ])
            ->add('category', ChoiceType::class, [
                "label" => "Catégorie de la recette :",
                'required' => true,
                'data' => '-- Choisissez une catégorie --',
                'choices' => [
                    'Choisissez une catégorie' => [
                        '-- Choisissez une catégorie --' => '',
                        'Entrée' => 'entrée',
                        'Plat' => 'plat',
                        'Dessert' => 'dessert',
                        'Cocktail & boisson' => 'cockaitl & boisson',
                        'Sauce' => 'sauce',
                    ]
                ]
            ])
            ->add('content', TextareaType::class, [
                "label" => "Contenu :"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', ChoiceType::class, [
                "label" => "Catégorie de la recette :",
                'required' => true,
                'data' => '-- Choisissez une catégorie --',
                'choices' => [
                    'Choisissez une catégorie' => [
                        'Entrée' => 'entrée',
                        'Plat' => 'plat',
                        'Dessert' => 'dessert',
                        'Cocktail & boisson' => 'cocktail & boisson',
                        'Sauce' => 'sauce',
                    ]
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}

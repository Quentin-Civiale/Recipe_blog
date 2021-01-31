<?php

namespace App\Form;

use App\Entity\Recipe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotNull;

class RecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                "label" => "Titre :",
                "attr" => [
                    "class" => "form-control",
                    "type" => "text"
                ],
            ])
            ->add('category', CategoryType::class, [
                "label" => false,
                "attr" => [
                    "class" => ""
                ],
            ])
            ->add('content', TextareaType::class, [
                "label" => "Contenu :",
                "attr" => [
                    "class" => "form-control",
                    "white-space" => "pre-wrap"
                ],
            ])
            ->add('file', FileType::class, [
                "label" => "Choisir une photo pour illustrer la recette :",
                "required" => false,
                "mapped" => false,
                "constraints" => [
                    new Image(),
                    new NotNull([
                        "groups" => "create"
                    ])
                ],
                "attr" => [
                    "class" => "form-control"
                ],
            ])
            ->add('note', TextareaType::class, [
                "label" => "Notes :",
                "attr" => [
                    "class" => "form-control",
                    "white-space" => "pre-wrap"
                ],
            ])
            ->add('preparationTime', TimeType::class, [
                "label" => "Temps de préparation :",
                "required" => false
            ])
            ->add('cookingTime', TimeType::class, [
                "label" => "Temps de cuisson :",
                "required" => false
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

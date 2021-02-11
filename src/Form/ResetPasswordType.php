<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ResetPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'first_options' => [
                    'label' => 'Mot de passe :',
                    'attr' => [
                        'class' => 'form-control'
                    ],
                ],
                'second_options' => [
                    'label' => 'Confirmez votre mot de passe :',
                    'attr' => [
                        'class' => 'form-control'
                    ],
                ],
                'invalid_message' => 'La confirmation n\'est pas similaire au mot de passe.',
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 8]),
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class UserType
 * @package App\Form
 */
class UserType extends AbstractType
{
    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email :',
                'attr' => [
                    'class' => 'form-control',
                    'type' => 'text',
                    'placeholder' => 'mail@exemple.com',
                ],
            ])
            ->add('pseudo', TextType::class, [
                'label' => 'Pseudo :',
                'attr' => [
                    'class' => 'form-control',
                    'type' => 'text',
                    'placeholder' => 'Appelez-moi Cyril Lignac !'
                ],
            ])
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

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault("data_class", User::class);
    }

}
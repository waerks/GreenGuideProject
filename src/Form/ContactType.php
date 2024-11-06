<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name', TextType::class, [
            'label' => 'Nom',
            'constraints' => [
                new NotBlank(['message' => 'Le nom est requis.']),
            ],
        ])
        ->add('forname', TextType::class, [
            'label' => 'Prénom',
            'constraints' => [
                new NotBlank(['message' => 'Le prénom est requis.']),
            ],
        ])
        ->add('email', EmailType::class, [
            'label' => 'Adresse mail',
            'constraints' => [
                new NotBlank(['message' => 'L\'adresse mail est requise.']),
            ],
        ])
        ->add('message', TextareaType::class, [
            'label' => 'Votre message',
            'constraints' => [
                new NotBlank(['message' => 'Le message est requis.']),
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}

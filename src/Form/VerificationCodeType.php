<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints as Assert;

class VerificationCodeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('code', TextType::class, [
                'label' => 'Code de vérification (6 chiffres)',
                'attr' => [
                    'class' => 'form-control text-center',
                    'maxlength' => 6,
                    'pattern' => '[0-9]{6}',
                    'placeholder' => '000000'
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le code de vérification est obligatoire']),
                    new Assert\Regex([
                        'pattern' => '/^\d{6}$/',
                        'message' => 'Le code doit contenir exactement 6 chiffres'
                    ])
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
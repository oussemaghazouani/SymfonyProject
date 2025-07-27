<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Validator\Constraints as Assert;

class ClientRegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('identifiant', TextType::class, [
                'label' => 'Identifiant',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'L\'identifiant est obligatoire'])
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'L\'email est obligatoire']),
                    new Assert\Email(['message' => 'Veuillez entrer un email valide'])
                ]
            ])
            ->add('motdepasse', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'label' => 'Mot de passe',
                    'attr' => ['class' => 'form-control'],
                    'constraints' => [
                        new Assert\NotBlank(['message' => 'Le mot de passe est obligatoire']),
                        new Assert\Length([
                            'min' => 6,
                            'minMessage' => 'Le mot de passe doit contenir au moins {{ limit }} caractères'
                        ])
                    ]
                ],
                'second_options' => [
                    'label' => 'Confirmer le mot de passe',
                    'attr' => ['class' => 'form-control']
                ],
                'invalid_message' => 'Les mots de passe ne correspondent pas.'
            ])
            ->add('userType', ChoiceType::class, [
                'label' => 'Type d\'utilisateur',
                'choices' => [
                    'Personne physique' => 'personne_physique',
                    'Entreprise' => 'entreprise'
                ],
                'expanded' => true,
                'multiple' => false,
                'attr' => ['class' => 'form-check-input'],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Veuillez choisir un type d\'utilisateur'])
                ]
            ])
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le nom est obligatoire'])
                ]
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le prénom est obligatoire'])
                ]
            ])
            ->add('nomEntreprise', TextType::class, [
                'label' => 'Nom de l\'entreprise',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'style' => 'display: none;'
                ]
            ])
            ->add('acceptTerms', CheckboxType::class, [
                'label' => 'J\'accepte les Termes et Conditions',
                'mapped' => false,
                'attr' => ['class' => 'form-check-input'],
                'constraints' => [
                    new Assert\IsTrue(['message' => 'Vous devez accepter les termes et conditions'])
                ]
            ])
            ->add('acceptPrivacy', CheckboxType::class, [
                'label' => 'J\'accepte les Politiques de confidentialité',
                'mapped' => false,
                'attr' => ['class' => 'form-check-input'],
                'constraints' => [
                    new Assert\IsTrue(['message' => 'Vous devez accepter les politiques de confidentialité'])
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
<?php
namespace App\Form;

use App\Entity\Produit;
use App\Entity\TypeProduit;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'required' => false, // Désactive la validation HTML5 "required"
                'label' => 'Nom',
                'constraints' => [
                    new NotBlank(['message' => 'Le nom est requis.']),
                    new Type(['type' => 'string', 'message' => 'Le nom doit être une chaîne de caractères.']),
                    new Regex([
                        'pattern' => '/^[A-Z][a-zA-Z\s]*$/',
                        'message' => 'Le nom doit commencer par une majuscule et contenir uniquement des lettres.',
                    ]),
                ],
                'attr' => [
                    'novalidate' => 'novalidate', // Désactive la validation HTML5
                ],
            ])
            ->add('prix', TextType::class, [
                'required' => false, // Désactive la validation HTML5 "required"
                'label' => 'Prix',
                'constraints' => [
                    new NotBlank(['message' => 'Le prix est requis.']),
                    new Type(['type' => 'string', 'message' => 'Le prix doit être un nombre entier.']),
                    new Regex([
                        'pattern' => '/^\d+$/',  // Assurer que seul un nombre entier est accepté
                        'message' => 'Le prix doit être un nombre entier.',
                    ]),
                ],
                'attr' => [
                    'novalidate' => 'novalidate', // Désactive la validation HTML5
                ],
            ])
            ->add('EstDisponible')
            ->add('Description', TextareaType::class, [
                'required' => false, // Désactive la validation HTML5 "required"
                'label' => 'Description',
                'constraints' => [
                    new NotBlank(['message' => 'La description est requise.']),
                ],
                'attr' => [
                    'novalidate' => 'novalidate', // Désactive la validation HTML5
                ],
            ])

            ->add('imageFile', FileType::class, [
                'label' => 'Image (JPG, PNG)',
                'mapped' => false, // This field is not directly mapped to the `image` property
                'required' => false,
            ])
            
            
        
        
            ->add('TypeP', EntityType::class, [
                'class' => TypeProduit::class,
                'choice_label' => 'Nom',
                'attr' => [
                    'novalidate' => 'novalidate', // Désactive la validation HTML5
                ],
            ]);
            
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
 
    // autres champs...


}

<?php

namespace App\Form;

use App\Entity\Comment;
use App\Form\CommentType;  
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content', TextareaType::class, [
                'label' => 'Votre Commentaire',
                'attr' => ['rows' => 4, 'class' => 'form-control'],
            ])
            ->add('save', SubmitType::class, ['label' => 'Ajouter Commentaire', 'attr' => ['class' => 'btn btn-primary mt-3']]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}

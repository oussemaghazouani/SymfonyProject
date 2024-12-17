<?php

namespace App\Form;

use App\Entity\Competitions;
use App\Entity\TypeCompetitions;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class Competitions1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('dateC', null, [
                'widget' => 'single_text',
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Bodybuilding' => 'bodybuilding',
                    'Kickboxing' => 'kickboxing',
                    'Boxing' => 'boxing',
                ],
               
            ])
            ->add('typeCompetition', EntityType::class, [
                'class' => TypeCompetitions::class,
                'choice_label' => 'name',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Competitions::class,
        ]);
    }
}

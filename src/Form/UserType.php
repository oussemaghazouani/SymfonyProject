<?php


namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;


class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $isAdmin = $options['is_admin'] ?? false;
        $signup = $options['is_signup'] ?? true;  
  
        if($signup)
        {
            $builder
            ->add('nom');
            
        }
        $builder
            ->add('EMAIL')
            ->add('motdepasse', PasswordType::class) 
            ->add('captcha', Recaptcha3Type::class, [
                'constraints' => new Recaptcha3(),
                
            ])
            
                
                
                
                
            
        ;


        if ($isAdmin) {
            $builder->add('role', ChoiceType::class, [
                'label' => 'Role',
                'choices' => [
                    'Admin' => 'ROLE_ADMIN',
                    'Client' => 'ROLE_CLIENT',
                ],
                'attr' => ['class' => 'form-control'],
                'placeholder' => 'Choose a role',
            ]);
        } else {
           
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'is_admin' => false,
            'is_signup' => true,

        ]);
    }
}

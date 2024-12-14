<?php


namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;  // Ensure this is correctly imported
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $isAdmin = $options['is_admin'] ?? false;
        $signup = $options['is_signup'] ?? true;

        if ($signup) {
            $builder->add('nom');
        }

        $builder
            ->add('EMAIL')
            ->add('motdepasse', PasswordType::class)
            ->add('recaptcha', Recaptcha3Type::class);


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

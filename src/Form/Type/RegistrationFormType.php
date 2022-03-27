<?php

declare(strict_types=1);

namespace App\Form\Type;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType as SymfonyPasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;

final class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'attr' => [
                    'placeholder' => 'label.username',
                ],
                'label' => 'label.username',
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'placeholder' => 'label.email',
                ],
                'label' => 'label.email',
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'label' => 'label.agree',
                'constraints' => [
                    new IsTrue([
                        'message' => 'agree_terms',
                    ]),
                ],
            ])
            ->add('password', SymfonyPasswordType::class, [
                'attr' => [
                    'autocomplete' => 'new-password',
                    'placeholder' => 'label.password',
                ],
                'label' => 'label.password',
                'constraints' => [
                    new Length([
                        'min' => 10,
                        'max' => 4096,
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'csrf_token_id' => 'authenticate',
        ]);
    }
}

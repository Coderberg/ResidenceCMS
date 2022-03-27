<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Valery Maslov
 * Date: 24.08.2018
 * Time: 10:07.
 */

namespace App\Form\Type;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'roles', ChoiceType::class, [
                    'choices' => [
                        'label.roles.admin' => 'ROLE_ADMIN',
                    ],
                    'expanded' => true,
                    'multiple' => true,
                    'label' => false,
                    'label_attr' => ['class' => 'switch-custom'],
                ]
            )
            ->add('username', null, [
                'label' => 'label.username',
            ])
            ->add('profile', ProfileType::class)
            ->add('email', null, [
                'label' => 'label.email',
            ])
            ->add(
                'email_verified', CheckboxType::class, [
                    'label' => 'label.email_verified',
                    'label_attr' => ['class' => 'switch-custom'],
                    'mapped' => false,
                    'required' => false,
                    'data' => null !== $options['data']->getEmailVerifiedAt(),
                ]
            )
            ->add('password', PasswordType::class, [
                'label' => 'label.password',
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

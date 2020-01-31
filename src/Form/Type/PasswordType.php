<?php

declare(strict_types=1);

namespace App\Form\Type;

use App\Validator\ConfirmPassword;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

final class PasswordType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('password', null, [
                'attr' => [
                    'autofocus' => true,
                    'class' => 'form-control',
                ],
                'label' => 'label.new_password',
                'constraints' => [new Length(['min' => 5, 'allowEmptyString' => false])],
            ])
            ->add('password_confirmation', null, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'label.confirm_password',
                'constraints' => [new Length(['min' => 5, 'allowEmptyString' => false]), new ConfirmPassword()],
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}

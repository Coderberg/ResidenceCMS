<?php

declare(strict_types=1);

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\LocaleType;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class LanguageType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'attr' => [
                'class' => 'form-control',
            ],
            'choice_loader' => null,
            'choices' => [
                'Select language' => '',
                'English' => 'en',
                'Nederlands' => 'nl',
                'Български' => 'bg',
                'Русский' => 'ru',
                'Magyar' => 'hu',
            ],
            'label' => 'label.locale',
        ]);
    }

    public function getParent(): string
    {
        return LocaleType::class;
    }
}

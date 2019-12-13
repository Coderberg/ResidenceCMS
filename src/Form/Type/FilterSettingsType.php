<?php

declare(strict_types=1);

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class FilterSettingsType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('show_filter_by_city', ChoiceType::class, [
                    'choices' => [
                        'option.off' => '0',
                        'option.on' => '1',
                    ],
                    'attr' => [
                        'class' => 'form-control',
                    ],
                    'label' => 'label.settings.show_filter_by_city',
                ]
            )
            ->add('show_filter_by_deal_type', ChoiceType::class, [
                    'choices' => [
                        'option.off' => '0',
                        'option.on' => '1',
                    ],
                    'attr' => [
                        'class' => 'form-control',
                    ],
                    'label' => 'label.settings.show_filter_by_deal_type',
                ]
            )
            ->add('show_filter_by_category', ChoiceType::class, [
                    'choices' => [
                        'option.off' => '0',
                        'option.on' => '1',
                    ],
                    'attr' => [
                        'class' => 'form-control',
                    ],
                    'label' => 'label.settings.show_filter_by_category',
                ]
            )
            ->add('show_filter_by_bedrooms', ChoiceType::class, [
                    'choices' => [
                        'option.off' => '0',
                        'option.on' => '1',
                    ],
                    'attr' => [
                        'class' => 'form-control',
                    ],
                    'label' => 'label.settings.show_filter_by_bedrooms',
                ]
            )
            ->add('show_filter_by_guests', ChoiceType::class, [
                    'choices' => [
                        'option.off' => '0',
                        'option.on' => '1',
                    ],
                    'attr' => [
                        'class' => 'form-control',
                    ],
                    'label' => 'label.settings.show_filter_by_guests',
                ]
            );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}

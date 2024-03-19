<?php

declare(strict_types=1);

namespace App\Form\Type;

use App\Entity\Currency;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Range;

final class MainSettingsType extends AbstractType
{
    private const CHOICES = [
        'option.off' => '0',
        'option.on' => '1',
    ];

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'attr' => [
                    'autofocus' => true,
                ],
                'label' => 'label.settings.name',
                'constraints' => [new Length(['min' => 2])],
            ])
            ->add('title', null, [
                'label' => 'label.settings.title',
                'constraints' => [new Length(['min' => 4])],
            ])
            ->add('meta_title', null, [
                'label' => 'label.meta_title',
                'constraints' => [new Length(['min' => 8])],
            ])
            ->add('meta_description', TextareaType::class, [
                'label' => 'label.meta_description',
                'constraints' => [new Length(['min' => 8])],
            ])
            ->add('custom_code', TextareaType::class, [
                'required' => false,
                'label' => 'label.settings.code',
            ])
            ->add('custom_footer_text', TextareaType::class, [
                'required' => false,
                'label' => 'label.settings.custom_footer_text',
            ])
            ->add('currency', EntityType::class, [
                'class' => Currency::class,
                'choice_label' => 'currency_title',
                'label' => 'label.settings.currency',
            ])
            ->add('items_per_page', null, [
                'label' => 'label.settings.limit',
                'constraints' => [new Range(['min' => 3, 'max' => 100])],
            ])
            ->add('show_similar_properties', ChoiceType::class, [
                'choices' => self::CHOICES,
                'label' => 'label.settings.show_similar_properties',
            ]
            )
            ->add('fixed_top_navbar', ChoiceType::class, [
                'choices' => self::CHOICES,
                'label' => 'label.settings.fixed_top_navbar',
            ]
            )
            ->add('show_language_selector', ChoiceType::class, [
                'choices' => self::CHOICES,
                'label' => 'label.settings.show_language_selector',
            ]
            )
            ->add('anyone_can_register', ChoiceType::class, [
                'choices' => self::CHOICES,
                'label' => 'label.settings.anyone_can_register',
            ]
            )
            ->add('show_bottom_bar', ChoiceType::class, [
                'choices' => self::CHOICES,
                'label' => 'label.settings.show_bottom_bar',
            ]
            )
            ->add('allow_html', ChoiceType::class, [
                'choices' => self::CHOICES,
                'label' => 'label.settings.allow_html',
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

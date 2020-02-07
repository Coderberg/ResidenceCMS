<?php

declare(strict_types=1);

namespace App\Form\Type;

use App\Entity\Feature;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class FeatureType extends AbstractType
{
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
                'label' => 'label.feature',
            ])
            ->add('icon', null, [
                'attr' => [
                    'rows' => '1',
                    'placeholder' => 'placeholder.example_icon',
                ],
                'label' => 'label.custom_icon',
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Feature::class,
        ]);
    }
}

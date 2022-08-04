<?php

declare(strict_types=1);

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Range;

final class MapSettingsType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ymaps_key', null, [
                'required' => false,
                'label' => 'label.settings.ymaps_key',
            ])
            ->add('map_center', null, [
                'attr' => [
                    'placeholder' => 'placeholder.map_center_example',
                ],
                'required' => false,
                'label' => 'label.settings.map_center',
            ])
            ->add('map_zoom', null, [
                'label' => 'label.settings.map_zoom',
                'constraints' => [new Range(['min' => 0, 'max' => 19])],
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

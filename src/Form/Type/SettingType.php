<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Valery Maslov
 * Date: 30.09.2018
 * Time: 11:08.
 */

namespace App\Form\Type;

use App\Entity\Setting;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class SettingType extends AbstractType
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
                    'class' => 'form-control',
                ],
                'label' => 'label.settings.name',
            ])
            ->add('title', null, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'label.settings.title',
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'label.settings.description',
            ])
            ->add('custom_code', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'required' => false,
                'label' => 'label.settings.code',
            ])
            ->add('items_per_page', null, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'label.settings.limit',
            ])
            ->add('ymaps_key', null, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'required' => false,
                'label' => 'label.settings.ymaps_key',
            ])
            ->add('map_center', null, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'placeholder.map_center_example',
                ],
                'required' => false,
                'label' => 'label.settings.map_center',
            ])
            ->add('map_zoom', null, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'label.settings.map_zoom',
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Setting::class,
        ]);
    }
}

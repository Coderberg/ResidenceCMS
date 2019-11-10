<?php

declare(strict_types=1);

namespace App\Form\Type;

use App\Entity\City;
use App\Entity\Metro;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class MetroType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('city', EntityType::class, [
                'class' => City::class,
                'choice_label' => 'name',
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'city',
            ])
            ->add('name', null, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'label.metro_station_name',
            ])
            ->add('slug', null, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'label.slug',
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Metro::class,
        ]);
    }
}

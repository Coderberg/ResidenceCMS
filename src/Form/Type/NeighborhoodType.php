<?php

declare(strict_types=1);

namespace App\Form\Type;

use App\Entity\City;
use App\Entity\Neighborhood;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class NeighborhoodType extends AbstractType
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
                'label' => 'city',
            ])
            ->add('name', null, [
                'label' => 'label.neighborhood_name',
            ])
            ->add('slug', null, [
                'label' => 'label.slug',
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Neighborhood::class,
        ]);
    }
}

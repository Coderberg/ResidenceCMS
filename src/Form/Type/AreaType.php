<?php

declare(strict_types=1);

namespace App\Form\Type;

use App\Entity\Area;
use App\Entity\City;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class AreaType extends AbstractType
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
                'label' => 'label.area_name',
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
            'data_class' => Area::class,
        ]);
    }
}

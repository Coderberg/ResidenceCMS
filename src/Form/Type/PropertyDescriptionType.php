<?php

declare(strict_types=1);

namespace App\Form\Type;

use App\Entity\PropertyDescription;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotNull;

final class PropertyDescriptionType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', null, [
                'label' => 'label.title',
                'attr' => [
                    'required' => true,
                ],
                'label_attr' => [
                    'class' => 'required',
                ],
                'constraints' => [
                    new NotNull(),
                ],
            ])
            ->add('meta_title', null, [
                'label' => 'label.meta_title',
            ])
            ->add('meta_description', null, [
                'label' => 'label.meta_description',
            ])
            ->add('content', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control summer-note',
                    'rows' => '7',
                    'required' => true,
                ],
                'label' => 'label.content',
                'constraints' => [
                    new NotNull(),
                ],
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PropertyDescription::class,
        ]);
    }
}

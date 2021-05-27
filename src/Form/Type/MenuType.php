<?php

declare(strict_types=1);

namespace App\Form\Type;

use App\Entity\Menu;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class MenuType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', null, [
                'attr' => [
                    'autofocus' => true,
                    'class' => 'form-control',
                ],
                'label' => 'label.link_text',
            ])
            ->add('url', null, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'label.url',
            ])
            ->add('locale', LanguageType::class)
            ->add('nofollow', null, [
                'attr' => [
                    'class' => 'custom-control-input',
                ],
                'label' => 'label.nofollow',
                'label_attr' => [
                    'class' => 'custom-control-label',
                ],
            ])
            ->add('new_tab', null, [
                'attr' => [
                    'class' => 'custom-control-input',
                ],
                'label' => 'label.new_tab',
                'label_attr' => [
                    'class' => 'custom-control-label',
                ],
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Menu::class,
        ]);
    }
}

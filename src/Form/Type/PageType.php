<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Valery Maslov
 * Date: 01.11.2018
 * Time: 11:13.
 */

namespace App\Form\Type;

use App\Entity\Page;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class PageType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', null, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'label.title',
            ])
            ->add('slug', null, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'label.slug',
            ])
            ->add('description', null, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'label.description',
            ])
            ->add('content', CKEditorType::class, [
                'config' => [
                    'uiColor' => '#ffffff',
                ],
                'label' => 'label.content',
            ])
            ->add('show_in_menu', null, [
                'attr' => [
                    'class' => 'custom-control-input',
                ],
                'label' => 'label.show_in_menu',
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
            'data_class' => Page::class,
        ]);
    }
}

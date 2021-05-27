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
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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
            ->add('locale', LanguageType::class)
            ->add('content', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control summer-note',
                    'rows' => '7',
                ],
                'label' => 'label.content',
                'required' => false,
            ])
            ->add('show_in_menu', null, [
                'attr' => [
                    'class' => 'custom-control-input',
                ],
                'label' => 'label.show_in_menu',
                'label_attr' => [
                    'class' => 'custom-control-label',
                ],
            ])
            ->add('add_contact_form', null, [
                'attr' => [
                    'class' => 'custom-control-input',
                ],
                'label' => 'label.add_contact_form',
                'label_attr' => [
                    'class' => 'custom-control-label',
                ],
            ])
            ->add('contact_email_address', null, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'placeholder.enter_email',
                ],
                'label' => 'label.contact_email_address',
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

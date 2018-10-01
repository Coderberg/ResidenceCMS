<?php
/**
 * Created by PhpStorm.
 * User: Valery Maslov
 * Date: 30.09.2018
 * Time: 11:08
 */

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use App\Entity\Setting;

final class SettingType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('homepage_title', null, [
                'attr' => [
                    'autofocus' => true,
                    'class' => 'form-control'
                ],
                'label' => 'label.homepage_title',
            ])
            ->add('homepage_description', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'label.homepage_description',
            ])
            ->add('homepage_meta_tags', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'required' => false,
                'label' => 'label.homepage_meta_tags',
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Setting::class,
        ]);
    }
}

<?php

declare(strict_types=1);

namespace App\Form\Type;

use App\Entity\Currency;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class CurrencyType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('currency_title', null, [
                'attr' => [
                    'autofocus' => true,
                ],
                'label' => 'label.currency_title',
            ])
            ->add('code', null, [
                'label' => 'label.code',
            ])
            ->add('symbol_left', null, [
                'label' => 'label.symbol_left',
            ])
            ->add('symbol_right', null, [
                'label' => 'label.symbol_right',
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Currency::class,
        ]);
    }
}

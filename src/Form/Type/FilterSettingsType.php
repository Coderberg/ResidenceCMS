<?php

declare(strict_types=1);

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class FilterSettingsType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('show_filter_by_city', ChoiceType::class, $this->getChoiceTypeFilterOptions('label.settings.show_filter_by_city'))
            ->add('show_filter_by_deal_type', ChoiceType::class, $this->getChoiceTypeFilterOptions('label.settings.show_filter_by_deal_type'))
            ->add('show_filter_by_category', ChoiceType::class, $this->getChoiceTypeFilterOptions('label.settings.show_filter_by_category'))
            ->add('show_filter_by_bedrooms', ChoiceType::class, $this->getChoiceTypeFilterOptions('label.settings.show_filter_by_bedrooms'))
            ->add('show_filter_by_guests', ChoiceType::class, $this->getChoiceTypeFilterOptions('label.settings.show_filter_by_guests'))
            ->add('show_filter_by_features', ChoiceType::class, $this->getChoiceTypeFilterOptions('label.settings.show_filter_by_features'));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }

    private function getChoiceTypeFilterOptions(string $label): array
    {
        return [
            'choices' => [
                'option.off' => '0',
                'option.on' => '1',
            ],
            'attr' => [
                'class' => 'form-control',
            ],
            'label' => $label,
        ];
    }
}

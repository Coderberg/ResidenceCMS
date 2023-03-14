<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Valery Maslov
 * Date: 16.08.2018
 * Time: 10:39.
 */

namespace App\Form\Type;

use App\Entity\Category;
use App\Entity\City;
use App\Entity\DealType;
use App\Entity\District;
use App\Entity\Feature;
use App\Entity\Metro;
use App\Entity\Neighborhood;
use App\Entity\Property;
use App\Entity\User;
use App\Form\EventSubscriber\AddAgentFieldSubscriber;
use App\Form\EventSubscriber\AddDistrictFieldSubscriber;
use App\Form\EventSubscriber\AddMetroFieldSubscriber;
use App\Form\EventSubscriber\AddNeighborhoodFieldSubscriber;
use App\Form\EventSubscriber\UpdateDistrictFieldSubscriber;
use App\Form\EventSubscriber\UpdateMetroFieldSubscriber;
use App\Form\EventSubscriber\UpdateNeighborhoodFieldSubscriber;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class PropertyType extends AbstractType
{
    public function __construct(private readonly Security $security)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('city', EntityType::class, [
                'class' => City::class,
                'choice_label' => 'name',
                'placeholder' => 'placeholder.select_city',
                'label' => 'label.city',
            ])
            ->add('district', EntityType::class, [
                'class' => District::class,
                'choice_label' => 'name',
                'placeholder' => 'placeholder.select_district',
                'label' => 'label.district',
                'required' => false,
                'choices' => [],
            ])
            ->add('neighborhood', EntityType::class, [
                'class' => Neighborhood::class,
                'choice_label' => 'name',
                'placeholder' => 'placeholder.select_neighborhood',
                'label' => 'label.neighborhood',
                'required' => false,
                'choices' => [],
            ])
            ->add('metro_station', EntityType::class, [
                'class' => Metro::class,
                'choice_label' => 'name',
                'placeholder' => 'placeholder.select_metro_station',
                'label' => 'label.metro_station_name',
                'required' => false,
                'choices' => [],
            ])
            ->add('dealType', EntityType::class, [
                'class' => DealType::class,
                'choice_label' => 'name',
                'label' => 'label.deal_type',
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'label' => 'label.category',
            ])
            ->add('bathrooms_number', null, [
                'label' => 'label.bathrooms_number',
            ])
            ->add('bedrooms_number', null, [
                'label' => 'label.bedrooms_number',
            ])
            ->add('max_guests', null, [
                'label' => 'label.max_guests',
            ])
            ->add('address', null, [
                'label' => 'label.address',
            ])
            ->add('latitude', null, [
                'label' => 'label.latitude',
            ])
            ->add('longitude', null, [
                'label' => 'label.longitude',
            ])
            ->add('show_map', CheckboxType::class, [
                'label' => 'label.show_map',
                'label_attr' => ['class' => 'switch-custom'],
                'required' => false,
            ])
            ->add('price', null, [
                'label' => 'label.price',
            ])
            ->add('price_type', null, [
                'label' => 'label.price_type',
            ])
            ->add('available_now', CheckboxType::class, [
                'label' => 'label.available_now',
                'label_attr' => ['class' => 'switch-custom'],
                'required' => false,
            ])
            ->add('features', EntityType::class, [
                'class' => Feature::class,
                'choice_label' => 'name',
                'multiple' => true,
                'required' => false,
                'label' => 'label.features',
            ])
            ->add('property_description', PropertyDescriptionType::class);

        $builder->addEventSubscriber(new AddNeighborhoodFieldSubscriber())
            ->get('city')->addEventSubscriber(new UpdateNeighborhoodFieldSubscriber());

        $builder->addEventSubscriber(new AddDistrictFieldSubscriber())
            ->get('city')->addEventSubscriber(new UpdateDistrictFieldSubscriber());

        $builder->addEventSubscriber(new AddMetroFieldSubscriber())
            ->get('city')->addEventSubscriber(new UpdateMetroFieldSubscriber());

        if ($this->security->isGranted('ROLE_ADMIN')) {
            $this->addFieldsForAdmin($builder);
        }
    }

    private function addFieldsForAdmin(FormBuilderInterface $builder): FormBuilderInterface
    {
        $builder
            ->add('priority_number', null, [
                'label' => 'label.priority_number',
                'required' => false,
            ])
            ->add('author', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'profile.full_name',
                'label' => 'label.agent',
            ])
            ->add('state', ChoiceType::class, [
                'label' => 'label.moderation_status',
                'choices' => [
                    'option.published' => 'published',
                    'option.private' => 'private',
                    'option.pending' => 'pending',
                    'option.rejected' => 'rejected',
                ],
            ]);

        $builder->addEventSubscriber(new AddAgentFieldSubscriber($this->security));

        return $builder;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Property::class,
        ]);
    }
}

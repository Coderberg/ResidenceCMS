<?php

declare(strict_types=1);

namespace App\Form\EventSubscriber;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class AddMetroFieldSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [FormEvents::POST_SET_DATA => 'onCitySelected'];
    }

    public function onCitySelected(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();
        $city = $data->getCity();

        if ($city) {
            $form->add('metro_station', EntityType::class, [
                'class' => 'App\Entity\Metro',
                'placeholder' => 'placeholder.select_metro_station',
                'choice_label' => 'name',
                'attr' => [
                    'class' => 'form-control',
                ],
                'required' => false,
                'label' => 'label.metro_station_name',
                'choices' => $city->getMetroStations(),
            ]);
        }
    }
}

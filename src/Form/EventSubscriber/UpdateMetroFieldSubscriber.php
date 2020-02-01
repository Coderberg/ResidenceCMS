<?php

declare(strict_types=1);

namespace App\Form\EventSubscriber;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class UpdateMetroFieldSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [FormEvents::POST_SUBMIT => 'onCityChanged'];
    }

    public function onCityChanged(FormEvent $event): void
    {
        $form = $event->getForm();

        if ($form->getData()) {
            // Metro stations
            $stations = $form->getData()->getMetroStations();

            $form->getParent()->add('metro_station', EntityType::class, [
                'class' => 'App\Entity\Metro',
                'placeholder' => 'placeholder.select_metro_station',
                'choice_label' => 'name',
                'attr' => [
                    'class' => 'form-control',
                ],
                'required' => false,
                'label' => 'label.metro_station_name',
                'choices' => $stations,
            ]);
        }
    }
}

<?php

declare(strict_types=1);

namespace App\Form\EventSubscriber;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class AddAreaFieldSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [FormEvents::POST_SET_DATA => 'onLocalitySelected'];
    }

    public function onLocalitySelected(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();
        $areas = $data->getArea();

        if ($areas) {
            $form->get('locality')->setData($areas->getLocality());

            $form->add('area', EntityType::class, [
                'class' => 'App\Entity\Area',
                'placeholder' => 'placeholder.select_area',
                'choice_label' => 'name',
                'attr' => [
                    'class' => 'form-control',
                ],
                'required' => false,
                'label' => 'label.area',
                'choices' => $areas->getLocality()->getAreas(),
            ]);
        }
    }
}

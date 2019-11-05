<?php

declare(strict_types=1);

namespace App\Form\EventSubscriber;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class UpdateAreaFieldSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [FormEvents::POST_SUBMIT => 'onCityChanged'];
    }

    public function onCityChanged(FormEvent $event)
    {
        $form = $event->getForm();

        if ($form->getData()) {
            // Areas of the city
            $areas = $form->getData()->getAreas();

            $form->getParent()->add('area', EntityType::class, [
                'class' => 'App\Entity\Area',
                'placeholder' => 'placeholder.select_area',
                'choice_label' => 'name',
                'attr' => [
                    'class' => 'form-control',
                ],
                'required' => false,
                'label' => 'label.area',
                'choices' => $areas,
            ]);
        }
    }
}

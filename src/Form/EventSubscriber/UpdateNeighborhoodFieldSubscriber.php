<?php

declare(strict_types=1);

namespace App\Form\EventSubscriber;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class UpdateNeighborhoodFieldSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [FormEvents::POST_SUBMIT => 'onCityChanged'];
    }

    public function onCityChanged(FormEvent $event)
    {
        $form = $event->getForm();

        if ($form->getData()) {
            // Neighborhoods of the city
            $neighborhoods = $form->getData()->getNeighborhoods();

            $form->getParent()->add('neighborhood', EntityType::class, [
                'class' => 'App\Entity\Neighborhood',
                'placeholder' => 'placeholder.select_neighborhood',
                'choice_label' => 'name',
                'attr' => [
                    'class' => 'form-control',
                ],
                'required' => false,
                'label' => 'label.neighborhood',
                'choices' => $neighborhoods,
            ]);
        }
    }
}

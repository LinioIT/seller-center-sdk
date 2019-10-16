<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Xml\Webhook;

use Linio\SellerCenter\Model\Webhook\Event;
use Linio\SellerCenter\Model\Webhook\Events;
use SimpleXMLElement;

class EventsFactory
{
    public static function makeFromWebhook(SimpleXMLElement $element): Events
    {
        $events = new Events();

        foreach ($element->Event as $eventAlias) {
            if (empty($eventAlias)) {
                continue;
            }

            $event = new Event((string) $eventAlias, null);
            $events->add($event);
        }

        return $events;
    }

    public static function make(SimpleXMLElement $element): Events
    {
        $events = new Events();

        foreach ($element->Entities->Entity as $entity) {
            foreach ($entity->Events->Event as $item) {
                $event = EventFactory::make($item);
                $events->add($event);
            }
        }

        return $events;
    }
}

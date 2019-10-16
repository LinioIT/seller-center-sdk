<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Xml\Webhook;

use Linio\SellerCenter\Exception\InvalidXmlStructureException;
use Linio\SellerCenter\Model\Webhook\Event;
use SimpleXMLElement;

class EventFactory
{
    public static function make(SimpleXMLElement $element): Event
    {
        if (!property_exists($element, 'EventAlias')) {
            throw new InvalidXmlStructureException('Event', 'EventAlias');
        }

        return new Event((string) $element->EventAlias, (string) $element->EventName);
    }
}

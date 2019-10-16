<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Xml\Webhook;

use Linio\SellerCenter\Exception\InvalidXmlStructureException;
use Linio\SellerCenter\Model\Webhook\Webhook;
use SimpleXMLElement;

class WebhookFactory
{
    public static function make(SimpleXMLElement $element): Webhook
    {
        if (!property_exists($element, 'WebhookId')) {
            throw new InvalidXmlStructureException('Webhook', 'WebhookId');
        }

        if (!property_exists($element, 'CallbackUrl')) {
            throw new InvalidXmlStructureException('Webhook', 'CallbackUrl');
        }

        if (!property_exists($element, 'WebhookSource')) {
            throw new InvalidXmlStructureException('Webhook', 'WebhookSource');
        }

        if (!property_exists($element, 'Events')) {
            throw new InvalidXmlStructureException('Webhook', 'Events');
        }

        $events = EventsFactory::makeFromWebhook($element->Events);

        return new Webhook(
            (string) $element->WebhookId,
            (string) $element->CallbackUrl,
            $events,
            (string) $element->WebhookSource
        );
    }
}

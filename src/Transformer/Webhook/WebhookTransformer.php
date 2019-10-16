<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Transformer\Webhook;

use SimpleXMLElement;

class WebhookTransformer
{
    public static function createWebhookAsXmlString(string $callbackUrl, array $events): string
    {
        $xml = new SimpleXMLElement('<Request/>');

        $body = $xml->addChild('Webhook');

        $body->addChild('CallbackUrl', $callbackUrl);

        $eventsBody = $body->addChild('Events');

        foreach ($events as $event) {
            $eventsBody->addChild('Event', $event->getAlias());
        }

        return (string) $xml->asXML();
    }

    public static function deleteWebhookAsXmlString(string $webhookId): string
    {
        $xml = new SimpleXMLElement('<Request/>');

        $xml->addChild('Webhook', $webhookId);

        return (string) $xml->asXML();
    }
}

<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Xml\Webhook;

use Linio\SellerCenter\Model\Webhook\Webhooks;
use SimpleXMLElement;

class WebhooksFactory
{
    public static function make(SimpleXMLElement $element): Webhooks
    {
        $webhooks = new Webhooks();

        foreach ($element->Webhooks->Webhook as $item) {
            $webhook = WebhookFactory::make($item);
            $webhooks->add($webhook);
        }

        return $webhooks;
    }
}

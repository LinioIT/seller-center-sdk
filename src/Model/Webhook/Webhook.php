<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model\Webhook;

use JsonSerializable;
use stdClass;

class Webhook implements JsonSerializable
{
    /**
     * @var string
     */
    protected $webhookId;

    /**
     * @var string
     */
    protected $callbackUrl;

    /**
     * @var string
     */
    protected $webhookSource;

    /**
     * @var Events
     */
    protected $events;

    public function __construct(string $webhookId, string $callbackUrl, Events $events, string $webhookSource = 'api')
    {
        $this->webhookId = $webhookId;
        $this->callbackUrl = $callbackUrl;
        $this->webhookSource = $webhookSource;
        $this->events = $events;
    }

    public function getWebhookId(): string
    {
        return $this->webhookId;
    }

    public function getCallbackUrl(): string
    {
        return $this->callbackUrl;
    }

    public function getWebhookSource(): string
    {
        return $this->webhookSource;
    }

    public function getEvents(): Events
    {
        return $this->events;
    }

    public function jsonSerialize(): stdClass
    {
        $serialized = new stdClass();
        $serialized->webhookId = $this->webhookId;
        $serialized->callbackUrl = $this->callbackUrl;
        $serialized->webhookSource = $this->webhookSource;
        $serialized->events = $this->events;

        return $serialized;
    }
}

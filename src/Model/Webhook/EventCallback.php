<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model\Webhook;

class EventCallback
{
    /**
     * @var Event
     */
    protected $event;

    /**
     * @var array
     */
    protected $payload;

    public function __construct(Event $event, array $payload)
    {
        $this->event = $event;
        $this->payload = $payload;
    }

    public function getEvent(): Event
    {
        return $this->event;
    }

    public function getPayload(): array
    {
        return $this->payload;
    }
}

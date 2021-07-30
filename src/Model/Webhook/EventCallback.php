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
     * @var mixed[]
     */
    protected $payload;

    /**
     * @param mixed[] $payload
     */
    public function __construct(Event $event, array $payload)
    {
        $this->event = $event;
        $this->payload = $payload;
    }

    public function getEvent(): Event
    {
        return $this->event;
    }

    /**
     * @return mixed[]
     */
    public function getPayload(): array
    {
        return $this->payload;
    }
}

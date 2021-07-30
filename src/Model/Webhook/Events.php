<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model\Webhook;

use JsonSerializable;
use Linio\SellerCenter\Contract\CollectionInterface;

class Events implements CollectionInterface, JsonSerializable
{
    /**
     * @var Event[]
     */
    protected $collection = [];

    public function findByAlias(string $alias): ?Event
    {
        if (!key_exists($alias, $this->collection)) {
            return null;
        }

        return $this->collection[$alias];
    }

    /**
     * @return Event[]
     */
    public function all(): array
    {
        return $this->collection;
    }

    public function add(Event $event): void
    {
        $this->collection[$event->getAlias()] = $event;
    }

    /**
     * @return mixed[]
     */
    public function jsonSerialize(): array
    {
        return array_values($this->collection);
    }
}

<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model\Webhook;

use Linio\SellerCenter\Contract\CollectionInterface;

class Webhooks implements CollectionInterface
{
    /**
     * @var Webhook[]
     */
    protected $collection = [];

    public function findByWebhookId(string $webhookId): ?Webhook
    {
        if (!key_exists($webhookId, $this->collection)) {
            return null;
        }

        return $this->collection[$webhookId];
    }

    public function all(): array
    {
        return $this->collection;
    }

    public function add(Webhook $webhook): void
    {
        $this->collection[$webhook->getWebhookId()] = $webhook;
    }
}

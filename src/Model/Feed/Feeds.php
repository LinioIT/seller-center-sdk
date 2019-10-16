<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model\Feed;

use Linio\SellerCenter\Contract\CollectionInterface;

class Feeds implements CollectionInterface
{
    /**
     * @var Feed[]
     */
    protected $collection = [];

    public function findById(int $feedId): ?Feed
    {
        if (!key_exists($feedId, $this->collection)) {
            return null;
        }

        return $this->collection[$feedId];
    }

    public function all(): array
    {
        return $this->collection;
    }

    public function add(Feed $feed): void
    {
        $this->collection[$feed->getId()] = $feed;
    }
}

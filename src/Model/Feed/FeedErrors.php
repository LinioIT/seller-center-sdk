<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model\Feed;

use JsonSerializable;
use Linio\SellerCenter\Contract\CollectionInterface;

class FeedErrors implements CollectionInterface, JsonSerializable
{
    /**
     * @var FeedError[]
     */
    protected $collection = [];

    public function all(): array
    {
        return $this->collection;
    }

    public function add(FeedError $error): void
    {
        $this->collection[] = $error;
    }

    /**
     * @return FeedError[]
     */
    public function jsonSerialize(): array
    {
        return $this->collection;
    }
}

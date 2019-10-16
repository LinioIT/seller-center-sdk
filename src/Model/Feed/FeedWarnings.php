<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model\Feed;

use JsonSerializable;
use Linio\SellerCenter\Contract\CollectionInterface;

class FeedWarnings implements CollectionInterface, JsonSerializable
{
    /**
     * @var FeedWarning[]
     */
    protected $collection = [];

    public function all(): array
    {
        return $this->collection;
    }

    public function add(FeedWarning $warning): void
    {
        $this->collection[] = $warning;
    }

    public function jsonSerialize(): array
    {
        return $this->collection;
    }
}

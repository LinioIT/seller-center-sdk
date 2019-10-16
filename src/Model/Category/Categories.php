<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model\Category;

use JsonSerializable;
use Linio\SellerCenter\Contract\CollectionInterface;

class Categories implements CollectionInterface, JsonSerializable
{
    /**
     * @var Category[]
     */
    protected $collection = [];

    public function all(): array
    {
        return $this->collection;
    }

    public function add(Category $category): void
    {
        $this->collection[] = $category;
    }

    public function jsonSerialize(): array
    {
        return $this->collection;
    }
}

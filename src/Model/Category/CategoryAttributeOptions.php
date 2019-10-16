<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model\Category;

use JsonSerializable;
use Linio\SellerCenter\Contract\CollectionInterface;

class CategoryAttributeOptions implements CollectionInterface, JsonSerializable
{
    /**
     * @var CategoryAttributeOption[]
     */
    protected $collection = [];

    /**
     * @return CategoryAttributeOption[]
     */
    public function all(): array
    {
        return $this->collection;
    }

    public function add(CategoryAttributeOption $option): void
    {
        $this->collection[] = $option;
    }

    public function jsonSerialize(): array
    {
        return $this->collection;
    }
}

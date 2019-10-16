<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model\Category;

use Linio\SellerCenter\Contract\CollectionInterface;

class CategoryAttributes implements CollectionInterface
{
    /**
     * @var CategoryAttribute[]
     */
    protected $collection = [];

    /**
     * @return CategoryAttribute[]
     */
    public function all(): array
    {
        return $this->collection;
    }

    public function add(CategoryAttribute $categoryAttribute): void
    {
        $this->collection[] = $categoryAttribute;
    }
}

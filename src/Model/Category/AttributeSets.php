<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model\Category;

use Linio\SellerCenter\Contract\CollectionInterface;

class AttributeSets implements CollectionInterface
{
    /**
     * @var AttributeSet[]
     */
    protected $collection;

    public function all(): array
    {
        return $this->collection;
    }

    public function add(AttributeSet $attributeSet): void
    {
        $this->collection[] = $attributeSet;
    }
}

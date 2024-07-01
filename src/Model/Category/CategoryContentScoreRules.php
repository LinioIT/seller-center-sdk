<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model\Category;

use Linio\SellerCenter\Contract\CollectionInterface;

class CategoryContentScoreRules implements CollectionInterface
{
    /**
     * @var CategoryContentScoreRule[]
     */
    protected $collection;

    /**
     * @return CategoryContentScoreRule[]
     */
    public function all(): array
    {
        return $this->collection;
    }

    public function add(CategoryContentScoreRule $categoryContentScoreRule): void
    {
        $this->collection[] = $categoryContentScoreRule;
    }
}

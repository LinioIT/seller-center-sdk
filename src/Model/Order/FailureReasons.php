<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model\Order;

use Linio\SellerCenter\Contract\CollectionInterface;

class FailureReasons implements CollectionInterface
{
    /**
     * @var FailureReason[]
     */
    protected $collection;

    /**
     * @return FailureReason[]
     */
    public function all(): array
    {
        return $this->collection;
    }

    public function add(FailureReason $reason): void
    {
        $this->collection[] = $reason;
    }
}

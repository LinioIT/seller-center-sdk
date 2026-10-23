<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Contract;

interface CollectionInterface
{
    /**
     * @return mixed[]
     */
    public function all(): array;
}

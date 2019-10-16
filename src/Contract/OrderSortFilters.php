<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Contract;

interface OrderSortFilters
{
    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = 'updated_at';

    public const SORT_FILTERS = [
        self::CREATED_AT,
        self::UPDATED_AT,
    ];
}

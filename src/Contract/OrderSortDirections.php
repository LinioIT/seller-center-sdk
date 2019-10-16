<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Contract;

interface OrderSortDirections
{
    public const ASC = 'ASC';
    public const DESC = 'DESC';

    public const SORT_DIRECTIONS = [
        self::ASC,
        self::DESC,
    ];
}

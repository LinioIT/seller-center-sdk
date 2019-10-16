<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Contract;

interface OrderStatus
{
    public const PENDING = 'pending';
    public const CANCELED = 'canceled';
    public const READY_TO_SHIP = 'ready_to_ship';
    public const DELIVERED = 'delivered';
    public const RETURNED = 'returned';
    public const SHIPPED = 'shipped';
    public const FAILED = 'failed';

    public const STATUS = [
        self::PENDING,
        self::CANCELED,
        self::READY_TO_SHIP,
        self::DELIVERED,
        self::RETURNED,
        self::SHIPPED,
        self::FAILED,
    ];
}

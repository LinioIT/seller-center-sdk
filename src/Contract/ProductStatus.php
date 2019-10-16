<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Contract;

interface ProductStatus
{
    public const ACTIVE = 'active';
    public const INACTIVE = 'inactive';
    public const DELETED = 'deleted';

    public const STATUS = [
        self::ACTIVE,
        self::INACTIVE,
        self::DELETED,
    ];
}

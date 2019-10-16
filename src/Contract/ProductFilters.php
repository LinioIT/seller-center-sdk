<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Contract;

interface ProductFilters
{
    public const ALL = 'all';
    public const LIVE = 'live';
    public const INACTIVE = 'inactive';
    public const DELETED = 'deleted';
    public const IMAGE_MISSING = 'image-missing';
    public const PENDING = 'pending';
    public const REJECTED = 'rejected';
    public const SOLD_OUT = 'sold-out';

    public const FILTERS = [
        self::ALL,
        self::LIVE,
        self::INACTIVE,
        self::DELETED,
        self::IMAGE_MISSING,
        self::PENDING,
        self::REJECTED,
        self::SOLD_OUT,
    ];
}

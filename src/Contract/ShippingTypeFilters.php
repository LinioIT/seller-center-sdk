<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Contract;

interface ShippingTypeFilters
{
    public const DROPSHIPPING = 'dropshipping';
    public const FBF = 'own_warehouse';

    public const SHIPPING_TYPES = [
        self::DROPSHIPPING,
        self::FBF,
    ];
}

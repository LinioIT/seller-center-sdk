<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Contract;

interface ProductConditionTypes
{
    public const CONDITION_NEW = 'Nuevo';
    public const CONDITION_RECONDITIONED = 'Reacondicionado';

    public const CONDITION_TYPES = [
        self::CONDITION_NEW,
        self::CONDITION_RECONDITIONED,
    ];
}

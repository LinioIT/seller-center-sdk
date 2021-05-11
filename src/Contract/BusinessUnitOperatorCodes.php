<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Contract;

interface BusinessUnitOperatorCodes
{
    public const FALABELLA_CHILE = 'facl';
    public const FALABELLA_PERU = 'fape';
    public const LINIO_CHILE = 'licl';
    public const LINIO_PERU = 'lipe';
    public const SODIMAC_CHILE = 'socl';
    public const SODIMAC_PERU = 'sope';

    public const OPERATOR_CODES = [
        self::FALABELLA_CHILE,
        self::FALABELLA_PERU,
        self::LINIO_CHILE,
        self::LINIO_PERU,
        self::SODIMAC_CHILE,
        self::SODIMAC_PERU,
    ];
}

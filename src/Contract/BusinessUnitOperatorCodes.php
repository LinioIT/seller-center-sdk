<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Contract;

interface BusinessUnitOperatorCodes
{
    public const FALA_CHILE = 'facl';
    public const FALA_PERU = 'fape';
    public const FALA_MEXICO = 'famx';
    public const FALA_COLOMBIA = 'faco';

    public const CODE_CHILE = 'cl';
    public const CODE_PERU = 'pe';
    public const CODE_MEXICO = 'mx';
    public const CODE_COLOMBIA = 'co';

    public const OPERATOR_CODES = [
        self::FALA_CHILE,
        self::FALA_PERU,
        self::FALA_MEXICO,
        self::FALA_COLOMBIA,
    ];

    public const COUNTRY_CODES = [
        self::CODE_CHILE,
        self::CODE_PERU,
        self::CODE_MEXICO,
        self::CODE_COLOMBIA,
    ];

    public const COUNTRY_OPERATOR = [
        self::CODE_CHILE => self::FALA_CHILE,
        self::CODE_PERU => self::FALA_PERU,
        self::CODE_MEXICO => self::FALA_MEXICO,
        self::CODE_COLOMBIA => self::FALA_COLOMBIA,
    ];
}

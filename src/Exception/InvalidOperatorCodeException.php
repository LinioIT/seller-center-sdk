<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Exception;

use Linio\SellerCenter\Contract\BusinessUnitOperatorCodes;

class InvalidOperatorCodeException extends InvalidArgumentValueException
{
    const OPERATOR_FIELD = 'operator code';

    public function __construct()
    {
        parent::__construct(self::OPERATOR_FIELD, BusinessUnitOperatorCodes::COUNTRY_OPERATOR);
    }
}

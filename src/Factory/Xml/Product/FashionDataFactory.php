<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Xml\Product;

use Linio\SellerCenter\Model\Product\FashionData;
use SimpleXMLElement;

class FashionDataFactory
{
    public static function make(SimpleXMLElement $element): ?FashionData
    {
        if (isset($element->Color) || isset($element->ColorBasico) || isset($element->Size)) {
            return new FashionData(
                (string) $element->Color ?? null,
                (string) $element->ColorBasico ?? null,
                (string) $element->Size ?? null
            );
        }

        return null;
    }
}

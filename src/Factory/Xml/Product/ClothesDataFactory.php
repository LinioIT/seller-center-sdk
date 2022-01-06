<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Xml\Product;

use Linio\SellerCenter\Model\Product\ClothesData;
use SimpleXMLElement;

class ClothesDataFactory
{
    public static function make(SimpleXMLElement $element): ?ClothesData
    {
        if (isset($element->Color) || isset($element->ColorBasico) || isset($element->Size)) {
            return new ClothesData(
                (string) $element->Color ?? null,
                (string) $element->ColorBasico ?? null,
                (string) $element->Size ?? null
            );
        }

        return null;
    }
}

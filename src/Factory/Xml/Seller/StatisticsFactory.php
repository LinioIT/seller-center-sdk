<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Xml\Seller;

use SimpleXMLElement;

class StatisticsFactory
{
    public static function make(SimpleXMLElement $xml): array
    {
        $statistics = [];
        $statistics['Products'] = [];
        $statistics['Orders'] = [];

        foreach ($xml->Products->Status->children() as $element) {
            $statistics['Products'][$element->getName()] = (string) $element;
        }
        $statistics['Products']['Total'] = (string) $xml->Products->Total;

        foreach ($xml->Orders->Status->children() as $element) {
            $statistics['Orders'][$element->getName()] = (string) $element;
        }
        $statistics['Orders']['Total'] = (string) $xml->Orders->Total;

        return $statistics;
    }
}

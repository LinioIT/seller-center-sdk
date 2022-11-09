<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Xml\Seller;

use Linio\SellerCenter\Model\Seller\Statistic;
use SimpleXMLElement;

class StatisticsFactory
{
    public static function make(SimpleXMLElement $xml): Statistic
    {
        $statistics = Statistic::build();

        foreach ($xml->Products->Status->children() as $element) {
            $statistics->addProductStatistic($element->getName(), (int) $element);
        }
        $statistics->addProductStatistic('Total', (int) $xml->Products->Total);

        foreach ($xml->Orders->Status->children() as $element) {
            $statistics->addOrderStatistic($element->getName(), (int) $element);
        }
        $statistics->addOrderStatistic('Total', (int) $xml->Orders->Total);

        return $statistics;
    }
}

<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Xml\Seller;

use Linio\SellerCenter\LinioTestCase;
use Linio\SellerCenter\Model\Seller\Statistic;

class StatisticTest extends LinioTestCase
{
    public function testItSetsValuesCorrectly(): void
    {
        $body = $this->getSchema('Seller/Statistics.xml');
        $xml = simplexml_load_string($body);

        $statistics = StatisticsFactory::make($xml->Body);

        $statistics = Statistic::build();

        $statistics->addProductStatistic('Total', 123);
        $statistics->addProductStatistic('Category', 124);
        $statistics->addOrderStatistic('Total', 125);
        $statistics->addOrderStatistic('Category', 126);

        $this->assertEquals($statistics->getProductStatistic('Total'), 123);
        $this->assertEquals($statistics->getProductStatistic('Category'), 124);
        $this->assertEquals($statistics->getOrderStatistic('Total'), 125);
        $this->assertEquals($statistics->getOrderStatistic('Category'), 126);

        $this->assertIsArray($statistics->getProductStatistics());
        $this->assertEquals(count($statistics->getProductStatistics()), 2);
        $this->assertIsArray($statistics->getOrderStatistics());
        $this->assertEquals(count($statistics->getOrderStatistics()), 2);
    }
}

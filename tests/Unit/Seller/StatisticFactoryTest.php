<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Xml\Seller;

use Linio\Component\Util\Json;
use Linio\SellerCenter\LinioTestCase;
use Linio\SellerCenter\Model\Seller\Statistic;

class StatisticFactoryTest extends LinioTestCase
{
    public function testItProcessAnXml(): void
    {
        $body = $this->getSchema('Seller/Statistics.xml');
        $xml = simplexml_load_string($body);

        $statistics = StatisticsFactory::make($xml->Body);

        $this->assertJsonStringEqualsJsonString($this->getSchema('Seller/Statistics.json'), Json::encode($statistics));

        $this->assertInstanceOf(Statistic::class, $statistics);
    }
}

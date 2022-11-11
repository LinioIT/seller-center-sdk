<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Xml\Seller;

use Linio\Component\Util\Json;
use Linio\SellerCenter\LinioTestCase;

class StatisticFactoryTest extends LinioTestCase
{
    public function testItProcessAnXml(): void
    {
        $body = $this->getSchema('Seller/Statistics.xml');
        $xml = simplexml_load_string($body);

        $statistics = StatisticsFactory::make($xml->Body);

        $this->assertJsonStringEqualsJsonString($this->getSchema('Seller/Statistics.json'), Json::encode($statistics));

        $this->assertIsArray($statistics->getProductStatistics());
        $this->assertEquals(count($statistics->getProductStatistics()), 10);
        $this->assertIsArray($statistics->getOrderStatistics());
        $this->assertEquals(count($statistics->getOrderStatistics()), 18);

        $this->assertEquals($statistics->getProductStatistic('Total'), 6053);
        $this->assertEquals($statistics->getProductStatistic('Active'), 5036);
        $this->assertEquals($statistics->getProductStatistic('All'), 5963);
        $this->assertEquals($statistics->getProductStatistic('Deleted'), 90);
        $this->assertEquals($statistics->getProductStatistic('ImageMissing'), 1156);
        $this->assertEquals($statistics->getProductStatistic('Inactive'), 927);
        $this->assertEquals($statistics->getProductStatistic('Live'), 1486);
        $this->assertEquals($statistics->getProductStatistic('Pending'), 103);
        $this->assertEquals($statistics->getProductStatistic('PoorQuality'), 1790);
        $this->assertEquals($statistics->getProductStatistic('SoldOut'), 2084);
        $this->assertEquals($statistics->getOrderStatistic('Canceled'), 436);
        $this->assertEquals($statistics->getOrderStatistic('Delivered'), 2824);
        $this->assertEquals($statistics->getOrderStatistic('Digital'), 0);
        $this->assertEquals($statistics->getOrderStatistic('Economy'), 1);
        $this->assertEquals($statistics->getOrderStatistic('Express'), 0);
        $this->assertEquals($statistics->getOrderStatistic('Failed'), 45);
        $this->assertEquals($statistics->getOrderStatistic('NoExtInvoiceKey'), 3113);
        $this->assertEquals($statistics->getOrderStatistic('NotPrintedPending'), 4);
        $this->assertEquals($statistics->getOrderStatistic('NotPrintedReadyToShip'), 0);
        $this->assertEquals($statistics->getOrderStatistic('Pending'), 4);
        $this->assertEquals($statistics->getOrderStatistic('ReadyToShip'), 4);
        $this->assertEquals($statistics->getOrderStatistic('ReturnRejected'), 2);
        $this->assertEquals($statistics->getOrderStatistic('ReturnShippedByCustomer'), 0);
        $this->assertEquals($statistics->getOrderStatistic('ReturnWaitingForApproval'), 0);
        $this->assertEquals($statistics->getOrderStatistic('Returned'), 123);
        $this->assertEquals($statistics->getOrderStatistic('Shipped'), 10);
        $this->assertEquals($statistics->getOrderStatistic('Standard'), 3);
        $this->assertEquals($statistics->getOrderStatistic('Total'), 3449);
    }
}

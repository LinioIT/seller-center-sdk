<?php

declare(strict_types=1);

namespace Linio\SellerCenter;

use Exception;
use Linio\Component\Util\Json;
use Linio\SellerCenter\Application\Configuration;

class SellerManagerTest extends LinioTestCase
{
    use ClientHelper;

    public function testItReturnsStatistics(): void
    {
        $body = $this->getSchema('Seller/Statistics.xml');

        $client = $this->createClientWithResponse($body);

        $parameters = $this->getParameters();
        $configuration = new Configuration($parameters['key'], $parameters['username'], $parameters['endpoint'], $parameters['version']);

        $sdkClient = new SellerCenterSdk($configuration, $client);

        $result = $sdkClient->seller()->getStatistics();

        $this->assertIsArray($result->getProductStatistics());
        $this->assertEquals(count($result->getProductStatistics()), 10);
        $this->assertIsArray($result->getOrderStatistics());
        $this->assertEquals(count($result->getOrderStatistics()), 18);

        $this->assertJsonStringEqualsJsonString($this->getSchema('Seller/Statistics.json'), Json::encode($result));

        $this->assertEquals($result->getProductStatistic('Total'), 6053);
        $this->assertEquals($result->getProductStatistic('Active'), 5036);
        $this->assertEquals($result->getProductStatistic('All'), 5963);
        $this->assertEquals($result->getProductStatistic('Deleted'), 90);
        $this->assertEquals($result->getProductStatistic('ImageMissing'), 1156);
        $this->assertEquals($result->getProductStatistic('Inactive'), 927);
        $this->assertEquals($result->getProductStatistic('Live'), 1486);
        $this->assertEquals($result->getProductStatistic('Pending'), 103);
        $this->assertEquals($result->getProductStatistic('PoorQuality'), 1790);
        $this->assertEquals($result->getProductStatistic('SoldOut'), 2084);
        $this->assertEquals($result->getOrderStatistic('Canceled'), 436);
        $this->assertEquals($result->getOrderStatistic('Delivered'), 2824);
        $this->assertEquals($result->getOrderStatistic('Digital'), 0);
        $this->assertEquals($result->getOrderStatistic('Economy'), 1);
        $this->assertEquals($result->getOrderStatistic('Express'), 0);
        $this->assertEquals($result->getOrderStatistic('Failed'), 45);
        $this->assertEquals($result->getOrderStatistic('NoExtInvoiceKey'), 3113);
        $this->assertEquals($result->getOrderStatistic('NotPrintedPending'), 4);
        $this->assertEquals($result->getOrderStatistic('NotPrintedReadyToShip'), 0);
        $this->assertEquals($result->getOrderStatistic('Pending'), 4);
        $this->assertEquals($result->getOrderStatistic('ReadyToShip'), 4);
        $this->assertEquals($result->getOrderStatistic('ReturnRejected'), 2);
        $this->assertEquals($result->getOrderStatistic('ReturnShippedByCustomer'), 0);
        $this->assertEquals($result->getOrderStatistic('ReturnWaitingForApproval'), 0);
        $this->assertEquals($result->getOrderStatistic('Returned'), 123);
        $this->assertEquals($result->getOrderStatistic('Shipped'), 10);
        $this->assertEquals($result->getOrderStatistic('Standard'), 3);
        $this->assertEquals($result->getOrderStatistic('Total'), 3449);
    }

    public function testItThrowsAnExceptionWhenTheResponseIsAnError(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('E0125: Test Error');

        $body = '<?xml version="1.0" encoding="UTF-8"?>
        <ErrorResponse>
            <Head>
                <RequestAction>GetStatistics</RequestAction>
                <ErrorType>Sender</ErrorType>
                <ErrorCode>125</ErrorCode>
                <ErrorMessage>E0125: Test Error</ErrorMessage>
            </Head>
            <Body/>
        </ErrorResponse>';

        $client = $this->createClientWithResponse($body, 400);

        $env = $this->getParameters();

        $configuration = new Configuration($env['key'], $env['username'], $env['endpoint'], $env['version']);

        $sdkClient = new SellerCenterSdk($configuration, $client);

        $sdkClient->seller()->getStatistics();
    }
}

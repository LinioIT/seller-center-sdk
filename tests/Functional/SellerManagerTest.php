<?php

declare(strict_types=1);

namespace Linio\SellerCenter;

use Exception;
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

        $this->assertIsArray($result);
        $this->assertEquals($result['Products']['Total'], 6053);
        $this->assertEquals($result['Products']['Active'], 5036);
        $this->assertEquals($result['Products']['All'], 5963);
        $this->assertEquals($result['Products']['Deleted'], 90);
        $this->assertEquals($result['Products']['ImageMissing'], 1156);
        $this->assertEquals($result['Products']['Inactive'], 927);
        $this->assertEquals($result['Products']['Live'], 1486);
        $this->assertEquals($result['Products']['Pending'], 103);
        $this->assertEquals($result['Products']['PoorQuality'], 1790);
        $this->assertEquals($result['Products']['SoldOut'], 2084);
        $this->assertEquals($result['Orders']['Canceled'], 436);
        $this->assertEquals($result['Orders']['Delivered'], 2824);
        $this->assertEquals($result['Orders']['Digital'], 0);
        $this->assertEquals($result['Orders']['Economy'], 1);
        $this->assertEquals($result['Orders']['Express'], 0);
        $this->assertEquals($result['Orders']['Failed'], 45);
        $this->assertEquals($result['Orders']['NoExtInvoiceKey'], 3113);
        $this->assertEquals($result['Orders']['NotPrintedPending'], 4);
        $this->assertEquals($result['Orders']['NotPrintedReadyToShip'], 0);
        $this->assertEquals($result['Orders']['Pending'], 4);
        $this->assertEquals($result['Orders']['ReadyToShip'], 4);
        $this->assertEquals($result['Orders']['ReturnRejected'], 2);
        $this->assertEquals($result['Orders']['ReturnShippedByCustomer'], 0);
        $this->assertEquals($result['Orders']['ReturnWaitingForApproval'], 0);
        $this->assertEquals($result['Orders']['Returned'], 123);
        $this->assertEquals($result['Orders']['Shipped'], 10);
        $this->assertEquals($result['Orders']['Standard'], 3);
        $this->assertEquals($result['Orders']['Total'], 3449);
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

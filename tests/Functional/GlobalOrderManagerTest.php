<?php

declare(strict_types=1);

namespace Linio\SellerCenter;

use Linio\SellerCenter\Application\Configuration;
use Linio\SellerCenter\Response\SuccessResponse;

class GlobalOrderManagerTest extends LinioTestCase
{
    use ClientHelper;

    public function testItReturnsSuccessResponseWhenSetInvoiceNumber(): void
    {
        $orderItemId = 1;
        $invoiceNumber = '123132465465465465456';
        $documentLink = 'https://fakeInvoice.pdf';

        $body = sprintf(
            $this->getSchema('Order/SetInvoiceNumberSuccessResponse.xml'),
            'SetInvoiceNumber',
            $orderItemId,
            $invoiceNumber
        );

        $client = $this->createClientWithResponse($body);

        $parameters = $this->getParameters();

        $configuration = new Configuration($parameters['key'], $parameters['username'], $parameters['endpoint'], $parameters['version']);

        $sdkClient = new SellerCenterSdk($configuration, $client);

        $response = $sdkClient->globalOrders()->setInvoiceNumber(
            $orderItemId,
            $invoiceNumber,
            $documentLink
        );

        $this->assertInstanceOf(SuccessResponse::class, $response);
    }
}

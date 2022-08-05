<?php

declare(strict_types=1);

namespace Linio\SellerCenter;

use Linio\SellerCenter\Application\Configuration;
use Linio\SellerCenter\Model\Order\OrderItem;
use Linio\SellerCenter\Response\SuccessResponse;

class GlobalOrderManagerTest extends LinioTestCase
{
    use ClientHelper;

    public function testItReturnsSuccessResponseWhenSetInvoiceNumberInGlobal(): void
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

    public function testItReturnsUpdatedOrderItemsWhenSettingStatusToReadyToShipInGlobal(): void
    {

        $body = sprintf(
            $this->getOrdersResponse('Order/SetOrderStatusSuccessResponse.xml'),
            'SetStatusToReadyToShip',
            'OrderItems',
            1
        );

        $client = $this->createClientWithResponse($body);

        $parameters = $this->getParameters();

        $configuration = new Configuration($parameters['key'], $parameters['username'], $parameters['endpoint'], $parameters['version']);

        $sdkClient = new SellerCenterSdk($configuration, $client);

        $orderItems = $sdkClient->globalOrders()->setStatusToReadyToShip(
            [1],
            'deliveryType',
            'MPDS-200131783-9800'
        );

        $this->assertIsArray($orderItems);
        $this->assertContainsOnlyInstancesOf(OrderItem::class, $orderItems);
        $this->assertEquals('MPDS-200131783-9800', current($orderItems)->getPackageId());
        $this->assertEquals('123456', current($orderItems)->getPurchaseOrderId());
        $this->assertEquals('ABC-123456', current($orderItems)->getPurchaseOrderNumber());
    }

    public function testItReturnsUpdatedOrderItemsWhenSettingStatusToPackedByMarketplaceInGlobal(): void
    {
        $orderItemId = 1;
        $body = sprintf(
            $this->getOrdersResponse('Order/SetOrderStatusSuccessResponse.xml'),
            'SetStatusToPackedByMarketplace',
            'OrderItems',
            $orderItemId
        );

        $client = $this->createClientWithResponse($body);

        $parameters = $this->getParameters();

        $configuration = new Configuration($parameters['key'], $parameters['username'], $parameters['endpoint'], $parameters['version']);

        $sdkClient = new SellerCenterSdk($configuration, $client);

        $orderItems = $sdkClient->globalOrders()->setStatusToPackedByMarketplace(
            [$orderItemId],
            'deliveryType'
        );

        $this->assertIsArray($orderItems);
        $this->assertContainsOnlyInstancesOf(OrderItem::class, $orderItems);
        $this->assertEquals($orderItemId, current($orderItems)->getOrderItemId());
        $this->assertEquals(123456, current($orderItems)->getPurchaseOrderId());
        $this->assertEquals('ABC-123456', current($orderItems)->getPurchaseOrderNumber());
        $this->assertEquals('MPDS-200131783-9800', current($orderItems)->getPackageId());
    }

    public function getOrdersResponse(string $schema = 'Order/OrdersResponse.xml'): string
    {
        return $this->getSchema($schema);
    }
}

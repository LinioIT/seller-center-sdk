<?php

declare(strict_types=1);

namespace Linio\SellerCenter;

use Linio\SellerCenter\Application\Configuration;
use Linio\SellerCenter\Factory\Xml\Order\OrderItemsFactory;
use Linio\SellerCenter\Model\Order\OrderItem;
use Linio\SellerCenter\Response\SuccessResponse;

class OrderManagerTest extends LinioTestCase
{
    use ClientHelper;

    public function testItSetOrderItemsImei(): void
    {
        $client = $this->createClientWithResponse($this->getSchema('Order/SetImeiResponse.xml'));
        $parameters = $this->getParameters();

        $configuration = new Configuration($parameters['key'], $parameters['username'], $parameters['endpoint'], $parameters['version']);
        $sdkClient = new SellerCenterSdk($configuration, $client);
        $simpleXml = simplexml_load_string($this->getSchema('Order/OrderItemsResponse.xml'));

        $orderItems = OrderItemsFactory::make($simpleXml->Body);

        $result = $sdkClient->orders()->setOrderItemsImei(
            $orderItems->all()
        );

        $this->assertIsArray($result);

        $this->assertContainsOnlyInstancesOf(OrderItem::class, $result);
    }

    public function testItReturnsSuccessResponseWhenSetInvoiceNumber(): void
    {
        $orderItemId = 1;
        $invoiceNumber = '123132465465465465456';

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

        $response = $sdkClient->orders()->setInvoiceNumber(
            $orderItemId,
            $invoiceNumber
        );

        $this->assertInstanceOf(SuccessResponse::class, $response);
    }

    public function testItReturnsUpdatedOrderItemsWhenSettingStatusToReadyToShip(): void
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

        $orderItems = $sdkClient->orders()->setStatusToReadyToShip(
            [1],
            'deliveryType',
            'shippingProvider',
            'nxsqonoqsnoc'
        );

        $this->assertIsArray($orderItems);
        $this->assertContainsOnlyInstancesOf(OrderItem::class, $orderItems);
        $this->assertEquals('123456', current($orderItems)->getPurchaseOrderId());
        $this->assertEquals('ABC-123456', current($orderItems)->getPurchaseOrderNumber());
    }

    public function testItReturnsUpdatedOrderItemsWhenSettingStatusToPackedByMarketplace(): void
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

        $orderItems = $sdkClient->orders()->setStatusToPackedByMarketplace(
            [$orderItemId],
            'deliveryType',
            'shippingProvider',
            'nxsqonoqsnoc',
            '2kn412on3io1b3o'
        );

        $this->assertIsArray($orderItems);
        $this->assertContainsOnlyInstancesOf(OrderItem::class, $orderItems);
        $this->assertEquals($orderItemId, current($orderItems)->getOrderItemId());
        $this->assertEquals(123456, current($orderItems)->getPurchaseOrderId());
        $this->assertEquals('ABC-123456', current($orderItems)->getPurchaseOrderNumber());
    }

    public function getOrdersResponse(string $schema = 'Order/OrdersResponse.xml'): string
    {
        return $this->getSchema($schema);
    }
}

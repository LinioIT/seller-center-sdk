<?php

declare(strict_types=1);

namespace Linio\SellerCenter;

use Linio\SellerCenter\Factory\Xml\Order\OrderItemsFactory;
use Linio\SellerCenter\Model\Order\OrderItem;
use Linio\SellerCenter\Response\SuccessResponse;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Log\LoggerInterface;

class OrderManagerTest extends LinioTestCase
{
    use ClientHelper;

    /**
     * @var ObjectProphecy
     */
    protected $logger;

    public function prepareLogTest(bool $debug): void
    {
        $this->logger = $this->prophesize(LoggerInterface::class);

        $this->logger->debug(
            Argument::type('string'),
            Argument::type('array')
        )->shouldBeCalled();

        if (!$debug) {
            $this->logger->debug(
                Argument::type('string'),
                Argument::type('array')
            )->shouldNotBeCalled();
        }
    }

    public function testItSetOrderItemsImei(): void
    {
        $sdkClient = $this->getSdkClient($this->getSchema('Order/SetImeiResponse.xml'));
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

        $sdkClient = $this->getSdkClient($body);

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

        $sdkClient = $this->getSdkClient($body);

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

        $sdkClient = $this->getSdkClient($body);

        $orderItems = $sdkClient->orders()->setStatusToPackedByMarketplace(
            [$orderItemId],
            'deliveryType',
            'shippingProvider',
            'nxsqonoqsnoc'
        );

        $this->assertIsArray($orderItems);
        $this->assertContainsOnlyInstancesOf(OrderItem::class, $orderItems);
        $this->assertEquals($orderItemId, current($orderItems)->getOrderItemId());
        $this->assertEquals(123456, current($orderItems)->getPurchaseOrderId());
        $this->assertEquals('ABC-123456', current($orderItems)->getPurchaseOrderNumber());
    }

    /**
     * @dataProvider debugParameter
     */
    public function testItLogsDependingOnDebugParamWhenSetOrderItemsImeiSuccessResponse(bool $debug): void
    {
        $this->prepareLogTest($debug);
        $sdkClient = $this->getSdkClient($this->getSchema('Order/SetImeiResponse.xml'), $this->logger);
        $simpleXml = simplexml_load_string($this->getSchema('Order/OrderItemsResponse.xml'));

        $orderItems = OrderItemsFactory::make($simpleXml->Body);

        $sdkClient->orders()->setOrderItemsImei(
            $orderItems->all(),
            $debug
        );
    }

    /**
     * @dataProvider debugParameter
     */
    public function testItLogsDependingOnDebugParamWhenSetInvoiceNumberSuccessResponse(bool $debug): void
    {
        $orderItemId = 1;
        $invoiceNumber = '123132465465465465456';

        $body = sprintf(
            $this->getSchema('Order/SetInvoiceNumberSuccessResponse.xml'),
            'SetInvoiceNumber',
            $orderItemId,
            $invoiceNumber
        );

        $this->prepareLogTest($debug);
        $sdkClient = $this->getSdkClient($body, $this->logger);

        $sdkClient->orders()->setInvoiceNumber(
            $orderItemId,
            $invoiceNumber,
            $debug
        );
    }

    /**
     * @dataProvider debugParameter
     */
    public function testItLogsDependingOnDebugParamWhenSetStatusToReadyToShipSuccessResponse(bool $debug): void
    {
        $orderItemId = 1;
        $body = sprintf(
            $this->getOrdersResponse('Order/SetOrderStatusSuccessResponse.xml'),
            'SetStatusToReadyToShip',
            'OrderItems',
            $orderItemId
        );

        $this->prepareLogTest($debug);
        $sdkClient = $this->getSdkClient($body, $this->logger);

        $sdkClient->orders()->setStatusToReadyToShip(
            [$orderItemId],
            'deliveryType',
            'shippingProvider',
            'MPDS-200131783-9800',
            $debug
        );
    }

    /**
     * @dataProvider debugParameter
     */
    public function testItLogsDependingOnDebugParamWhenSetStatusToPackedByMarketplaceSuccessResponse(bool $debug): void
    {
        $orderItemId = 1;
        $body = sprintf(
            $this->getOrdersResponse('Order/SetOrderStatusSuccessResponse.xml'),
            'SetStatusToPackedByMarketplace',
            'OrderItems',
            $orderItemId
        );

        $this->prepareLogTest($debug);
        $sdkClient = $this->getSdkClient($body, $this->logger);

        $sdkClient->orders()->setStatusToPackedByMarketplace(
            [$orderItemId],
            'deliveryType',
            'shippingProvider',
            'trackingNumber',
            $debug
        );
    }

    public function getOrdersResponse(string $schema = 'Order/OrdersResponse.xml'): string
    {
        return $this->getSchema($schema);
    }

    public function debugParameter()
    {
        return [
            [false],
            [true],
        ];
    }
}

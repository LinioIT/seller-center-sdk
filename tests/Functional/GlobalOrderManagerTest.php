<?php

declare(strict_types=1);

namespace Linio\SellerCenter;

use DateTime;
use Linio\SellerCenter\Exception\InvalidDomainException;
use Linio\SellerCenter\Model\Order\InvoiceDocument;
use Linio\SellerCenter\Model\Order\OrderItem;
use Linio\SellerCenter\Model\Order\OrderItems;
use Linio\SellerCenter\Response\FeedResponse;
use Linio\SellerCenter\Response\SuccessJsonResponse;
use Linio\SellerCenter\Response\SuccessResponse;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Log\LoggerInterface;

class GlobalOrderManagerTest extends LinioTestCase
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

    public function testItReturnsSuccessResponseWhenSetInvoiceDocument(): void
    {
        $feedId = 'abdcd887-5c53-4636-8799-9b6f4b0176d7';

        $body = sprintf(
            $this->getSchema('Order/SetInvoiceDocumentSuccessResponse.xml'),
            $feedId
        );

        $sdkClient = $this->getSdkClient($body);

        $response = $sdkClient->globalOrders()->setInvoiceDocument(
            [1],
            'test123',
            'BOLETA',
            $this->getSchema('Order/InvoiceDocument.xml')
        );

        $this->assertInstanceOf(FeedResponse::class, $response);
        $this->assertEquals($response->getRequestId(), $feedId);
    }

    public function testItReturnsSuccessResponseWhenUploadInvoiceDocument(): void
    {
        $orderItems = new OrderItems();
        $orderItem = OrderItem::fromStatus(21, 123123, '123123', 'packageID123');
        $orderItems->add($orderItem);

        $invoiceDocument = new InvoiceDocument(
            '13123',
            new DateTime(),
            'BOLETA',
            'FACL',
            'qwertyuiopasdfghjklzxcvbnm',
            $orderItems
        );

        $body = $this->getSchema('Response/InvoiceDocumentSuccessResponse.json');
        $sdkClient = $this->getSdkClient($body);

        $response = $sdkClient->globalOrders()->uploadInvoiceDocument(
            $invoiceDocument
        );
        $this->assertInstanceOf(SuccessJsonResponse::class, $response);
    }

    public function testItThrowsInvalidDomainExceptionWhenSetInvoiceDocumentInvalidDocumentType(): void
    {
        $this->expectException(InvalidDomainException::class);

        $sdkClient = $this->getSdkClient('');

        $sdkClient->globalOrders()->setInvoiceDocument(
            [1],
            'test123',
            'incorrect_document_type',
            $this->getSchema('Order/InvoiceDocument.xml')
        );
    }

    public function testItReturnsSuccessResponseWhenSetInvoiceNumberInGlobal(): void
    {
        $orderItemIds = [1];
        $invoiceNumber = '123132465465465465456';
        $documentLink = 'https://fakeInvoice.pdf';

        $body = sprintf(
            $this->getSchema('Order/SetInvoiceNumberSuccessResponse.xml'),
            'SetInvoiceNumber',
            end($orderItemIds),
            $invoiceNumber
        );

        $sdkClient = $this->getSdkClient($body);

        $response = $sdkClient->globalOrders()->setInvoiceNumber(
            $orderItemIds,
            $invoiceNumber,
            $documentLink
        );

        $this->assertInstanceOf(SuccessResponse::class, $response);
    }

    public function testItReturnsUpdatedOrderItemsWhenSettingStatusToReadyToShipInGlobal(): void
    {
        $orderItemId = 1;
        $body = sprintf(
            $this->getOrdersResponse('Order/SetOrderStatusSuccessResponse.xml'),
            'SetStatusToReadyToShip',
            'OrderItems',
            $orderItemId
        );

        $sdkClient = $this->getSdkClient($body);

        $orderItems = $sdkClient->globalOrders()->setStatusToReadyToShip(
            [$orderItemId],
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

        $sdkClient = $this->getSdkClient($body);

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

    /**
     * @dataProvider debugParameter
     */
    public function testItLogsDependingOnDebugParamWhenSetInvoiceNumberGlobalSuccessResponse(bool $debug): void
    {
        $orderItemIds = [1, 5];
        $invoiceNumber = '123132465465465465456';
        $documentLink = 'https://fakeInvoice.pdf';

        $body = sprintf(
            $this->getSchema('Order/SetInvoiceNumberSuccessResponse.xml'),
            'SetInvoiceNumber',
            end($orderItemIds),
            $invoiceNumber
        );

        $this->prepareLogTest($debug);
        $sdkClient = $this->getSdkClient($body, $this->logger);

        $sdkClient->globalOrders()->setInvoiceNumber(
            $orderItemIds,
            $invoiceNumber,
            $documentLink,
            $debug
        );
    }

    /**
     * @dataProvider debugParameter
     */
    public function testItLogsDependingOnDebugParamWhenSetInvoiceDocumentGlobalSuccessResponse(bool $debug): void
    {
        $feedId = 'abdcd887-5c53-4636-8799-9b6f4b0176d7';

        $body = sprintf(
            $this->getSchema('Order/SetInvoiceDocumentSuccessResponse.xml'),
            $feedId
        );

        $this->prepareLogTest($debug);
        $sdkClient = $this->getSdkClient($body, $this->logger);

        $sdkClient->globalOrders()->setInvoiceDocument(
            [1],
            'test123',
            'BOLETA',
            $this->getSchema('Order/InvoiceDocument.xml'),
            $debug
        );
    }

    /**
     * @dataProvider debugParameter
     */
    public function testItLogsDependingOnDebugParamWhenSetStatusToReadyToShipGlobalSuccessResponse(bool $debug): void
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

        $sdkClient->globalOrders()->setStatusToReadyToShip(
            [$orderItemId],
            'deliveryType',
            'MPDS-200131783-9800',
            $debug
        );
    }

    /**
     * @dataProvider debugParameter
     */
    public function testItLogsDependingOnDebugParamWhenSetStatusToPackedByMarketplaceGlobalSuccessResponse(bool $debug): void
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

        $sdkClient->globalOrders()->setStatusToPackedByMarketplace(
            [$orderItemId],
            'deliveryType',
            $debug
        );
    }

    public function debugParameter()
    {
        return [
            [false],
            [true],
        ];
    }

    public function getOrdersResponse(string $schema = 'Order/OrdersResponse.xml'): string
    {
        return $this->getSchema($schema);
    }
}

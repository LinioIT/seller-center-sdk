<?php

declare(strict_types=1);

namespace Linio\SellerCenter;

use DateTimeImmutable;
use Exception;
use Linio\SellerCenter\Application\Configuration;
use Linio\SellerCenter\Exception\EmptyArgumentException;
use Linio\SellerCenter\Exception\ErrorResponseException;
use Linio\SellerCenter\Exception\InvalidDomainException;
use Linio\SellerCenter\Model\Order\FailureReason;
use Linio\SellerCenter\Model\Order\Order;
use Linio\SellerCenter\Model\Order\OrderItem;
use Linio\SellerCenter\Model\Order\OrderItems;
use Linio\SellerCenter\Service\OrderManager;
use Prophecy\Argument;
use Psr\Log\LoggerInterface;

class OrdersManagerTest extends LinioTestCase
{
    use ClientHelper;

    public function testItReturnsAOrder(): void
    {
        $client = $this->createClientWithResponse($this->getOrdersResponse('Order/OrderResponse.xml'));

        $env = $this->getParameters();
        $configuration = new Configuration($env['key'], $env['username'], $env['endpoint'], $env['version']);

        $sdkClient = new SellerCenterSdk($configuration, $client);

        $orderId = 4687503;

        $result = $sdkClient->orders()->getOrder($orderId);

        $this->assertInstanceOf(Order::class, $result);
    }

    public function testItReturnsACollectionOfOrderItems(): void
    {
        $client = $this->createClientWithResponse($this->getOrdersResponse('Order/OrderItemsResponse.xml'));

        $env = $this->getParameters();
        $configuration = new Configuration($env['key'], $env['username'], $env['endpoint'], $env['version']);

        $sdkClient = new SellerCenterSdk($configuration, $client);

        $orderId = 6750999;

        $result = $sdkClient->orders()->getOrderItems($orderId);

        $this->assertIsArray($result);
        $this->assertContainsOnlyInstancesOf(OrderItem::class, $result);
    }

    public function testItReturnAMultipleCollectionsOfOrderItems(): void
    {
        $client = $this->createClientWithResponse($this->getOrdersResponse('Order/OrdersItemsResponse.xml'));

        $env = $this->getParameters();
        $configuration = new Configuration($env['key'], $env['username'], $env['endpoint'], $env['version']);

        $sdkClient = new SellerCenterSdk($configuration, $client);

        $ordersId = [4687808, 6653173];

        $result = $sdkClient->orders()->getMultipleOrderItems($ordersId);

        $this->assertIsArray($result);
        $this->assertContainsOnlyInstancesOf(Order::class, $result);

        foreach ($result as $order) {
            $this->assertInstanceOf(OrderItems::class, $order->getOrderItems());
            $this->assertContainsOnlyInstancesOf(OrderItem::class, $order->getOrderItems()->all());
        }
    }

    /**
     * @dataProvider dateTimesAndFilters
     */
    public function testItReturnsACollectionOfOrdersCreatedBetweenADateTime(
        ?DateTimeImmutable $createdAfter,
        ?DateTimeImmutable $createdBefore,
        string $sortBy,
        string $sortDirection,
        string $status
    ): void {
        $client = $this->createClientWithResponse($this->getOrdersResponse());

        $env = $this->getParameters();
        $configuration = new Configuration($env['key'], $env['username'], $env['endpoint'], $env['version']);

        $sdkClient = new SellerCenterSdk($configuration, $client);

        $result = $sdkClient->orders()->getOrdersCreatedBetween(
            $createdAfter,
            $createdBefore,
            OrderManager::DEFAULT_LIMIT,
            OrderManager::DEFAULT_OFFSET,
            $sortBy,
            $sortDirection
        );

        $this->assertIsArray($result);
        $this->assertContainsOnlyInstancesOf(Order::class, $result);
    }

    /**
     * @dataProvider dateTimesAndFilters
     */
    public function testItReturnsACollectionOfOrdersUpdatedBetweenADateTime(
        ?DateTimeImmutable $updatedAfter,
        ?DateTimeImmutable $updatedBefore,
        string $sortBy,
        string $sortDirection,
        string $status
    ): void {
        $client = $this->createClientWithResponse($this->getOrdersResponse());

        $env = $this->getParameters();
        $configuration = new Configuration($env['key'], $env['username'], $env['endpoint'], $env['version']);

        $sdkClient = new SellerCenterSdk($configuration, $client);

        $result = $sdkClient->orders()->getOrdersUpdatedBetween(
            $updatedAfter,
            $updatedBefore,
            OrderManager::DEFAULT_LIMIT,
            OrderManager::DEFAULT_OFFSET,
            $sortBy,
            $sortDirection
        );

        $this->assertIsArray($result);
        $this->assertContainsOnlyInstancesOf(Order::class, $result);
    }

    /**
     * @dataProvider dateTimesAndFilters
     */
    public function testItReturnsACollectionOfOrdersCreatedAfterADateTime(
        ?DateTimeImmutable $createdAfter,
        ?DateTimeImmutable $unused,
        string $sortBy,
        string $sortDirection,
        string $status
    ): void {
        $client = $this->createClientWithResponse($this->getOrdersResponse());

        $env = $this->getParameters();
        $configuration = new Configuration($env['key'], $env['username'], $env['endpoint'], $env['version']);

        $sdkClient = new SellerCenterSdk($configuration, $client);

        $result = $sdkClient->orders()->getOrdersCreatedAfter(
            $createdAfter,
            OrderManager::DEFAULT_LIMIT,
            OrderManager::DEFAULT_OFFSET,
            $sortBy,
            $sortDirection
        );

        $this->assertIsArray($result);
        $this->assertContainsOnlyInstancesOf(Order::class, $result);
    }

    /**
     * @dataProvider dateTimesAndFilters
     */
    public function testItReturnsACollectionOfOrdersCreatedBeforeADateTime(
        ?DateTimeImmutable $createdBefore,
        ?DateTimeImmutable $unused,
        string $sortBy,
        string $sortDirection,
        string $status
    ): void {
        $client = $this->createClientWithResponse($this->getOrdersResponse());

        $env = $this->getParameters();
        $configuration = new Configuration($env['key'], $env['username'], $env['endpoint'], $env['version']);

        $sdkClient = new SellerCenterSdk($configuration, $client);

        $result = $sdkClient->orders()->getOrdersCreatedBefore(
            $createdBefore,
            OrderManager::DEFAULT_LIMIT,
            OrderManager::DEFAULT_OFFSET,
            $sortBy,
            $sortDirection
        );

        $this->assertIsArray($result);
        $this->assertContainsOnlyInstancesOf(Order::class, $result);
    }

    /**
     * @dataProvider dateTimesAndFilters
     */
    public function testItReturnsACollectionOfOrdersUpdatedAfterADateTime(
        ?DateTimeImmutable $updatedAfter,
        ?DateTimeImmutable $unused,
        string $sortBy,
        string $sortDirection,
        string $status
    ): void {
        $client = $this->createClientWithResponse($this->getOrdersResponse());

        $env = $this->getParameters();
        $configuration = new Configuration($env['key'], $env['username'], $env['endpoint'], $env['version']);

        $sdkClient = new SellerCenterSdk($configuration, $client);

        $result = $sdkClient->orders()->getOrdersUpdatedAfter(
            $updatedAfter,
            OrderManager::DEFAULT_LIMIT,
            OrderManager::DEFAULT_OFFSET,
            $sortBy,
            $sortDirection
        );

        $this->assertIsArray($result);
        $this->assertContainsOnlyInstancesOf(Order::class, $result);
    }

    /**
     * @dataProvider dateTimesAndFilters
     */
    public function testItReturnsACollectionOfOrdersUpdatedBeforeADateTime(
        ?DateTimeImmutable $updatedBefore,
        ?DateTimeImmutable $unused,
        string $sortBy,
        string $sortDirection,
        string $status
    ): void {
        $client = $this->createClientWithResponse($this->getOrdersResponse());

        $env = $this->getParameters();
        $configuration = new Configuration($env['key'], $env['username'], $env['endpoint'], $env['version']);

        $sdkClient = new SellerCenterSdk($configuration, $client);

        $result = $sdkClient->orders()->getOrdersUpdatedBefore(
            $updatedBefore,
            OrderManager::DEFAULT_LIMIT,
            OrderManager::DEFAULT_OFFSET,
            $sortBy,
            $sortDirection
        );

        $this->assertIsArray($result);
        $this->assertContainsOnlyInstancesOf(Order::class, $result);
    }

    /**
     * @dataProvider dateTimesAndFilters
     */
    public function testItReturnsACollectionOfOrdersWithStatus(
        ?DateTimeImmutable $unused,
        ?DateTimeImmutable $unused2,
        string $sortBy,
        string $sortDirection,
        string $status
    ): void {
        $client = $this->createClientWithResponse($this->getOrdersResponse());

        $env = $this->getParameters();
        $configuration = new Configuration($env['key'], $env['username'], $env['endpoint'], $env['version']);

        $sdkClient = new SellerCenterSdk($configuration, $client);

        $result = $sdkClient->orders()->getOrdersWithStatus(
            $status,
            OrderManager::DEFAULT_LIMIT,
            OrderManager::DEFAULT_OFFSET,
            $sortBy,
            $sortDirection
        );

        $this->assertIsArray($result);
        $this->assertContainsOnlyInstancesOf(Order::class, $result);
    }

    public function testThrowExceptionWithAInvalidStatus(): void
    {
        $this->expectException(InvalidDomainException::class);

        $this->expectExceptionMessage('The parameter Status is invalid.');

        $client = $this->createClientWithResponse($this->getOrdersResponse());

        $env = $this->getParameters();
        $configuration = new Configuration($env['key'], $env['username'], $env['endpoint'], $env['version']);

        $sdkClient = new SellerCenterSdk($configuration, $client);

        $status = 'invalid status';

        $result = $sdkClient->orders()->getOrdersWithStatus(
            $status,
            OrderManager::DEFAULT_LIMIT,
            OrderManager::DEFAULT_OFFSET,
            OrderManager::DEFAULT_SORT_BY,
            OrderManager::DEFAULT_SORT_DIRECTION
        );

        $this->assertIsArray($result);
        $this->assertContainsOnlyInstancesOf(Order::class, $result);
    }

    /**
     * @dataProvider parametersFromGetOrders
     */
    public function testItReturnsACollectionOfOrdersFromParameters(
        ?DateTimeImmutable $createdAfter,
        ?DateTimeImmutable $createdBefore,
        ?DateTimeImmutable $updatedAfter,
        ?DateTimeImmutable $updatedBefore,
        string $sortBy,
        string $sortDirection,
        string $status
    ): void {
        $client = $this->createClientWithResponse($this->getOrdersResponse());

        $env = $this->getParameters();
        $configuration = new Configuration($env['key'], $env['username'], $env['endpoint'], $env['version']);

        $sdkClient = new SellerCenterSdk($configuration, $client);

        $result = $sdkClient->orders()->getOrdersFromParameters(
            $createdAfter,
            $createdBefore,
            $updatedAfter,
            $updatedBefore,
            $status,
            OrderManager::DEFAULT_LIMIT,
            OrderManager::DEFAULT_OFFSET,
            $sortBy,
            $sortDirection
        );

        $this->assertIsArray($result);
        $this->assertContainsOnlyInstancesOf(Order::class, $result);
    }

    public function testItThrowsAnExceptionWhenTheResponseIsAnError(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('E0125: Test Error');

        $body = sprintf(
            $this->getOrdersResponse('Order/ErrorResponse.xml'),
            'GetOrder',
            'Sender',
            125,
            'E0125: Test Error'
        );

        $client = $this->createClientWithResponse($body, 400);

        $parameters = $this->getParameters();
        $configuration = new Configuration($parameters['key'], $parameters['username'], $parameters['endpoint'], $parameters['version']);

        $sdkClient = new SellerCenterSdk($configuration, $client);

        $orderItems = $sdkClient->orders()->setStatusToReadyToShip([1], 'deliveryType', 'shippingProvider');

        $this->assertIsArray($orderItems);
        $this->assertContainsOnlyInstancesOf(OrderItem::class, $orderItems);

        $this->assertEquals('123456', current($orderItems)->getPurchaseOrderId());
        $this->assertEquals('ABC-123456', current($orderItems)->getPurchaseOrderNumber());
    }

    public function testItThrowsAnExceptionWhenParameterIsMissingInGetMultipleOrderItems(): void
    {
        $this->expectException(EmptyArgumentException::class);
        $this->expectExceptionMessage('The parameter OrderIdList should not be null.');

        $parameters = $this->getParameters();
        $configuration = new Configuration($parameters['key'], $parameters['username'], $parameters['endpoint'], $parameters['version']);

        $sdk = new SellerCenterSdk($configuration);

        $sdk->orders()->getMultipleOrderItems([]);
    }

    public function testItThrowsExceptionWhenSettingStatusToCanceledReturnErrorResponse(): void
    {
        $this->expectException(ErrorResponseException::class);

        $body = sprintf(
            $this->getOrdersResponse('Order/ErrorResponse.xml'),
            'SetStatusToCanceled',
            '',
            0,
            ''
        );
        $client = $this->createClientWithResponse($body, 400);

        $parameters = $this->getParameters();
        $configuration = new Configuration($parameters['key'], $parameters['username'], $parameters['endpoint'], $parameters['version']);

        $sdkClient = new SellerCenterSdk($configuration, $client);
        $sdkClient->orders()->setStatusToCanceled(1, 'someReason', 'someReasonDetail');
    }

    public function testItLogsWhenSettingStatusToCanceledReturnSuccessResponse(): void
    {
        $body = sprintf(
            $this->getOrdersResponse('Order/SetOrderStatusSuccessResponse.xml'),
            'SetStatusToCanceled',
            '',
            1
        );

        $client = $this->createClientWithResponse($body, 400);

        $parameters = $this->getParameters();
        $configuration = new Configuration($parameters['key'], $parameters['username'], $parameters['endpoint'], $parameters['version']);
        $logger = $this->prophesize(LoggerInterface::class);
        $logger->debug(
            Argument::type('string'),
            Argument::type('array')
        )->shouldBeCalled();
        $logger->info(
            Argument::type('string')
        )->shouldBeCalled();
        $sdkClient = new SellerCenterSdk($configuration, $client, $logger->reveal());
        $sdkClient->orders()->setStatusToCanceled(1, 'someReason', 'someReasonDetail');
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
        $this->assertEquals('MPDS-200131783-9800', current($orderItems)->getPackageId());
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
            'nxsqonoqsnoc',
            'MPDS-200131783-9800'
        );

        $this->assertIsArray($orderItems);
        $this->assertContainsOnlyInstancesOf(OrderItem::class, $orderItems);
        $this->assertEquals('MPDS-200131783-9800', current($orderItems)->getPackageId());
        $this->assertEquals('123456', current($orderItems)->getPurchaseOrderId());
        $this->assertEquals('ABC-123456', current($orderItems)->getPurchaseOrderNumber());
    }

    public function testItReturnFailureReasons(): void
    {
        $xml = $this->getOrdersResponse('Order/FailureReasonsSuccessResponse.xml');

        $client = $this->createClientWithResponse($xml);
        $parameters = $this->getParameters();

        $configuration = new Configuration($parameters['key'], $parameters['username'], $parameters['endpoint'], $parameters['version']);

        $sdkClient = new SellerCenterSdk($configuration, $client);

        $failureReasons = $sdkClient->orders()->getFailureReasons();

        $this->assertContainsOnlyInstancesOf(FailureReason::class, $failureReasons);
    }

    public function dateTimesAndFilters(): array
    {
        $date = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2018-09-01 00:00:00');

        return [
            [$date, $date, 'created_at', 'ASC', 'pending'],
            [$date, $date, 'updated_at', 'ASC', 'canceled'],
            [$date, $date, 'created_at', 'DESC', 'ready_to_ship'],
            [$date, $date, 'updated_at', 'DESC', 'delivered'],
            [$date, $date, 'created_at', 'ASC', 'returned'],
            [$date, $date, 'updated_at', 'DESC', 'shipped'],
            [$date, $date, 'created_at', 'ASC', 'failed'],
        ];
    }

    public function parametersFromGetOrders(): array
    {
        $date = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2018-09-01 00:00:00');

        return [
            [$date, $date, $date, $date, 'created_at', 'ASC', 'pending'],
            [null, $date, null, $date, 'updated_at', 'ASC', 'canceled'],
            [$date, null, $date, null, 'invalid', 'DESC', 'ready_to_ship'],
            [$date, $date, null, null, 'updated_at', 'invalid', 'delivered'],
            [null, $date, $date, null, 'created_at', 'ASC', 'returned'],
            [$date, null, null, $date, 'invalid', 'invalid', 'shipped'],
            [null, null, null, null, 'created_at', 'ASC', 'failed'],
        ];
    }

    public function getOrdersResponse(string $schema = 'Order/OrdersResponse.xml'): string
    {
        return $this->getSchema($schema);
    }
}

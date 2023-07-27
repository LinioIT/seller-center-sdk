<?php

declare(strict_types=1);

namespace Linio\SellerCenter;

use DateTime;
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
use Linio\SellerCenter\Model\Order\TrackingCode;
use Linio\SellerCenter\Service\OrderManager;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Log\LoggerInterface;

class BaseOrderManagerTest extends LinioTestCase
{
    use ClientHelper;

    /**
     * @var ObjectProphecy
     */
    protected $logger;

    private const ORDER_INIT_DATE = '-3 month';
    private const ORDER_END_DATE = '-2 week';

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

    public function testItReturnsAOrder(): void
    {
        $sdkClient = $this->getSdkClient($this->getOrdersResponse('Order/OrderResponse.xml'));

        $orderId = 4687503;

        $result = $sdkClient->orders()->getOrder($orderId);

        $this->assertInstanceOf(Order::class, $result);
    }

    public function testItReturnsACollectionOfOrderItems(): void
    {
        $sdkClient = $this->getSdkClient($this->getOrdersResponse('Order/OrderItemsResponse.xml'));

        $orderId = 6750999;

        $result = $sdkClient->orders()->getOrderItems($orderId);

        $this->assertIsArray($result);
        $this->assertContainsOnlyInstancesOf(OrderItem::class, $result);
    }

    public function testItReturnAMultipleCollectionsOfOrderItems(): void
    {
        $sdkClient = $this->getSdkClient($this->getOrdersResponse('Order/OrdersItemsResponse.xml'));

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
        $sdkClient = $this->getSdkClient($this->getOrdersResponse());

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
        $sdkClient = $this->getSdkClient($this->getOrdersResponse());

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
        $sdkClient = $this->getSdkClient($this->getOrdersResponse());

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
        $sdkClient = $this->getSdkClient($this->getOrdersResponse());

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
        $sdkClient = $this->getSdkClient($this->getOrdersResponse());

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
        $sdkClient = $this->getSdkClient($this->getOrdersResponse());

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
        $sdkClient = $this->getSdkClient($this->getOrdersResponse());

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

        $sdkClient = $this->getSdkClient($this->getOrdersResponse());

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
        $sdkClient = $this->getSdkClient($this->getOrdersResponse());

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

        $sdkClient = $this->getSdkClient($body, null, 400);

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
        $sdkClient = $this->getSdkClient($body, null, 400);
        $sdkClient->orders()->setStatusToCanceled(1, 'someReason', 'someReasonDetail');
    }

    /**
     * @dataProvider debugParameter
     */
    public function testItLogsDependingOnDebugParamWhenSettingStatusToCanceledReturnSuccessResponse(bool $debug): void
    {
        $body = sprintf(
            $this->getOrdersResponse('Order/SetOrderStatusSuccessResponse.xml'),
            'SetStatusToCanceled',
            '',
            1
        );

        $this->prepareLogTest($debug);
        $sdkClient = $this->getSdkClient($body, $this->logger);

        $sdkClient->orders()->setStatusToCanceled(
            1,
            'someReason',
            'someReasonDetail',
            $debug
        );
    }

    /**
     * @dataProvider debugParameter
     */
    public function testItLogsDependingOnDebugParamWhenGetOrdersWithStatusSuccessResponse(bool $debug): void
    {
        $body = $this->getOrdersResponse('Order/OrdersResponse.xml');
        $this->prepareLogTest($debug);
        $sdkClient = $this->getSdkClient($body, $this->logger);

        $this->logger->info(
            Argument::type('string')
        )->shouldBeCalled();

        if (!$debug) {
            $this->logger->info(
                Argument::type('string')
            )->shouldNotBeCalled();
        }

        $sdkClient->orders()->getOrdersWithStatus(
            'pending',
            100,
            100,
            'created_at',
            'asc',
            $debug
        );
    }

    /**
     * @dataProvider debugParameter
     */
    public function testItLogsDependingOnDebugParamWhenGetOrderItemsSuccessResponse(bool $debug): void
    {
        $body = $this->getOrdersResponse('Order/OrderItemsResponse.xml');
        $this->prepareLogTest($debug);
        $sdkClient = $this->getSdkClient($body, $this->logger);

        $this->logger->info(
            Argument::type('string')
        )->shouldBeCalled();

        if (!$debug) {
            $this->logger->info(
                Argument::type('string')
            )->shouldNotBeCalled();
        }

        $sdkClient->orders()->getOrderItems(
            1,
            $debug
        );
    }

    /**
     * @dataProvider debugParameter
     */
    public function testItLogsDependingOnDebugParamWhenGetMultipleOrderItemsSuccessResponse(bool $debug): void
    {
        $body = $this->getOrdersResponse('Order/OrdersItemsResponse.xml');
        $this->prepareLogTest($debug);
        $sdkClient = $this->getSdkClient($body, $this->logger);

        $this->logger->info(
            Argument::type('string')
        )->shouldBeCalled();

        if (!$debug) {
            $this->logger->info(
                Argument::type('string')
            )->shouldNotBeCalled();
        }

        $sdkClient->orders()->getMultipleOrderItems(
            [1],
            $debug
        );
    }

    /**
     * @dataProvider debugParameter
     */
    public function testItLogsDependingOnDebugParamWhenGetOrdersCreatedBetweenSuccessResponse(bool $debug): void
    {
        $body = $this->getOrdersResponse('Order/OrdersResponse.xml');
        $this->prepareLogTest($debug);
        $sdkClient = $this->getSdkClient($body, $this->logger);

        $this->logger->info(
            Argument::type('string')
        )->shouldBeCalled();

        if (!$debug) {
            $this->logger->info(
                Argument::type('string')
            )->shouldNotBeCalled();
        }

        $since = new DateTime(self::ORDER_INIT_DATE);
        $until = new DateTime(self::ORDER_END_DATE);
        $limit = 20;
        $offset = 0;
        $sortBy = '';
        $sortDirection = 'DESC';

        $sdkClient->orders()->getOrdersCreatedBetween(
            $since,
            $until,
            $limit,
            $offset,
            $sortBy,
            $sortDirection,
            $debug
        );
    }

    /**
     * @dataProvider debugParameter
     */
    public function testItLogsDependingOnDebugParamWhenGetOrdersUpdatedBetweenSuccessResponse(bool $debug): void
    {
        $body = $this->getOrdersResponse('Order/OrdersResponse.xml');
        $this->prepareLogTest($debug);
        $sdkClient = $this->getSdkClient($body, $this->logger);

        $this->logger->info(
            Argument::type('string')
        )->shouldBeCalled();

        if (!$debug) {
            $this->logger->info(
                Argument::type('string')
            )->shouldNotBeCalled();
        }

        $since = new DateTime(self::ORDER_INIT_DATE);
        $until = new DateTime(self::ORDER_END_DATE);
        $limit = 20;
        $offset = 0;
        $sortBy = '';
        $sortDirection = 'DESC';

        $sdkClient->orders()->getOrdersUpdatedBetween(
            $since,
            $until,
            $limit,
            $offset,
            $sortBy,
            $sortDirection,
            $debug
        );
    }

    /**
     * @dataProvider debugParameter
     */
    public function testItLogsDependingOnDebugParamWhenGetOrdersCreatedBeforeSuccessResponse(bool $debug): void
    {
        $body = $this->getOrdersResponse('Order/OrdersResponse.xml');
        $this->prepareLogTest($debug);
        $sdkClient = $this->getSdkClient($body, $this->logger);

        $this->logger->info(
            Argument::type('string')
        )->shouldBeCalled();

        if (!$debug) {
            $this->logger->info(
                Argument::type('string')
            )->shouldNotBeCalled();
        }

        $since = new DateTime(self::ORDER_INIT_DATE);
        $limit = 20;
        $offset = 0;
        $sortBy = '';
        $sortDirection = 'DESC';

        $sdkClient->orders()->getOrdersCreatedBefore(
            $since,
            $limit,
            $offset,
            $sortBy,
            $sortDirection,
            $debug
        );
    }

    /**
     * @dataProvider debugParameter
     */
    public function testItLogsDependingOnDebugParamWhenGetOrdersUpdatedBeforeSuccessResponse(bool $debug): void
    {
        $body = $this->getOrdersResponse('Order/OrdersResponse.xml');
        $this->prepareLogTest($debug);
        $sdkClient = $this->getSdkClient($body, $this->logger);

        $this->logger->info(
            Argument::type('string')
        )->shouldBeCalled();

        if (!$debug) {
            $this->logger->info(
                Argument::type('string')
            )->shouldNotBeCalled();
        }

        $since = new DateTime(self::ORDER_INIT_DATE);
        $limit = 20;
        $offset = 0;
        $sortBy = '';
        $sortDirection = 'DESC';

        $sdkClient->orders()->getOrdersUpdatedBefore(
            $since,
            $limit,
            $offset,
            $sortBy,
            $sortDirection,
            $debug
        );
    }

    /**
     * @dataProvider debugParameter
     */
    public function testItLogsDependingOnDebugParamWhenGetOrdersCreatedAfterSuccessResponse(bool $debug): void
    {
        $body = $this->getOrdersResponse('Order/OrdersResponse.xml');
        $this->prepareLogTest($debug);
        $sdkClient = $this->getSdkClient($body, $this->logger);

        $this->logger->info(
            Argument::type('string')
        )->shouldBeCalled();

        if (!$debug) {
            $this->logger->info(
                Argument::type('string')
            )->shouldNotBeCalled();
        }

        $since = new DateTime(self::ORDER_INIT_DATE);
        $limit = 20;
        $offset = 0;
        $sortBy = '';
        $sortDirection = 'DESC';

        $sdkClient->orders()->getOrdersCreatedAfter(
            $since,
            $limit,
            $offset,
            $sortBy,
            $sortDirection,
            $debug
        );
    }

    /**
     * @dataProvider debugParameter
     */
    public function testItLogsDependingOnDebugParamWhenGetOrdersUpdatedAfterSuccessResponse(bool $debug): void
    {
        $body = $this->getOrdersResponse('Order/OrdersResponse.xml');
        $this->prepareLogTest($debug);
        $sdkClient = $this->getSdkClient($body, $this->logger);

        $this->logger->info(
            Argument::type('string')
        )->shouldBeCalled();

        if (!$debug) {
            $this->logger->info(
                Argument::type('string')
            )->shouldNotBeCalled();
        }

        $since = new DateTime(self::ORDER_INIT_DATE);
        $limit = 20;
        $offset = 0;
        $sortBy = '';
        $sortDirection = 'DESC';

        $sdkClient->orders()->getOrdersUpdatedAfter(
            $since,
            $limit,
            $offset,
            $sortBy,
            $sortDirection,
            $debug
        );
    }

    /**
     * @dataProvider debugParameter
     */
    public function testItLogsDependingOnDebugParamWhenGetOrdersFromParametersSuccessResponse(bool $debug): void
    {
        $body = $this->getOrdersResponse('Order/OrdersResponse.xml');
        $this->prepareLogTest($debug);
        $sdkClient = $this->getSdkClient($body, $this->logger);

        $this->logger->info(
            Argument::type('string')
        )->shouldBeCalled();

        if (!$debug) {
            $this->logger->info(
                Argument::type('string')
            )->shouldNotBeCalled();
        }

        $limit = 20;
        $offset = 0;
        $sortBy = '';
        $sortDirection = 'DESC';

        $sdkClient->orders()->getOrdersFromParameters(
            null,
            null,
            null,
            null,
            'pending',
            $limit,
            $offset,
            $sortBy,
            $sortDirection,
            $debug
        );
    }

    /**
     * @dataProvider debugParameter
     */
    public function testItLogsDependingOnDebugParamWhenGetTrackingCodeSuccessResponse(bool $debug): void
    {
        $body = $this->getOrdersResponse('Order/TrackingCodeSucessResponse.xml');
        $this->prepareLogTest($debug);
        $sdkClient = $this->getSdkClient($body, $this->logger);

        $sdkClient->orders()->getTrackingCode(
            '10f9780b-380c-4625-8024-b166ece74453',
            'chilexpress',
            $debug
        );
    }

    /**
     * @dataProvider debugParameter
     */
    public function testItLogsDependingOnDebugParamWhenGetFailureReasonsSuccessResponse(bool $debug): void
    {
        $body = $this->getOrdersResponse('Order/FailureReasonsSuccessResponse.xml');
        $this->prepareLogTest($debug);
        $sdkClient = $this->getSdkClient($body, $this->logger);

        $this->logger->info(
            Argument::type('string')
        )->shouldBeCalled();

        if (!$debug) {
            $this->logger->info(
                Argument::type('string')
            )->shouldNotBeCalled();
        }

        $sdkClient->orders()->getFailureReasons($debug);
    }

    public function testItReturnFailureReasons(): void
    {
        $xml = $this->getOrdersResponse('Order/FailureReasonsSuccessResponse.xml');

        $sdkClient = $this->getSdkClient($xml);

        $failureReasons = $sdkClient->orders()->getFailureReasons();

        $this->assertContainsOnlyInstancesOf(FailureReason::class, $failureReasons);
    }

    public function testItGetTrackingCode(): void
    {
        $xml = $this->getOrdersResponse('Order/TrackingCodeSucessResponse.xml');
        $sdkClient = $this->getSdkClient($xml);

        $response = $sdkClient->orders()->getTrackingCode(
            '1e8382f4-70b6-4ffd-b479-f7373408d232',
            'chilexpress'
        );

        $this->assertInstanceOf(TrackingCode::class, $response);
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

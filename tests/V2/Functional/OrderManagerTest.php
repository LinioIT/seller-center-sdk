<?php

declare(strict_types=1);

namespace Linio\SellerCenter\V2\Functional;

use DateTime;
use DateTimeImmutable;
use Linio\SellerCenter\ClientHelper;
use Linio\SellerCenter\Exception\InvalidDomainException;
use Linio\SellerCenter\LinioTestCase;
use Linio\SellerCenter\V2\Model\Order\Order;
use Linio\SellerCenter\V2\Service\OrderManager;
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

        $result = $sdkClient->ordersV2()->getOrder($orderId);

        $this->assertInstanceOf(Order::class, $result);
    }

    /**
     * @dataProvider dateTimesAndFilters
     */
    public function testItReturnsACollectionOfOrdersCreatedBetweenADateTime(
        ?DateTimeImmutable $createdAfter,
        ?DateTimeImmutable $createdBefore,
        string $sortBy,
        string $sortDirection,
        string $status,
        ?string $dateFormat
    ): void {
        $sdkClient = $this->getSdkClient($this->getOrdersResponse());

        $result = $sdkClient->ordersV2()->getOrdersCreatedBetween(
            $createdAfter,
            $createdBefore,
            OrderManager::DEFAULT_LIMIT,
            OrderManager::DEFAULT_OFFSET,
            $sortBy,
            $sortDirection,
            $dateFormat
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
        string $status,
        ?string $dateFormat
    ): void {
        $sdkClient = $this->getSdkClient($this->getOrdersResponse());

        $result = $sdkClient->ordersV2()->getOrdersUpdatedBetween(
            $updatedAfter,
            $updatedBefore,
            OrderManager::DEFAULT_LIMIT,
            OrderManager::DEFAULT_OFFSET,
            $sortBy,
            $sortDirection,
            $dateFormat
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
        string $status,
        ?string $dateFormat
    ): void {
        $sdkClient = $this->getSdkClient($this->getOrdersResponse());

        $result = $sdkClient->ordersV2()->getOrdersCreatedAfter(
            $createdAfter,
            OrderManager::DEFAULT_LIMIT,
            OrderManager::DEFAULT_OFFSET,
            $sortBy,
            $sortDirection,
            $dateFormat
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
        string $status,
        ?string $dateFormat
    ): void {
        $sdkClient = $this->getSdkClient($this->getOrdersResponse());

        $result = $sdkClient->ordersV2()->getOrdersCreatedBefore(
            $createdBefore,
            OrderManager::DEFAULT_LIMIT,
            OrderManager::DEFAULT_OFFSET,
            $sortBy,
            $sortDirection,
            $dateFormat
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
        string $status,
        ?string $dateFormat
    ): void {
        $sdkClient = $this->getSdkClient($this->getOrdersResponse());

        $result = $sdkClient->ordersV2()->getOrdersUpdatedAfter(
            $updatedAfter,
            OrderManager::DEFAULT_LIMIT,
            OrderManager::DEFAULT_OFFSET,
            $sortBy,
            $sortDirection,
            $dateFormat
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
        string $status,
        ?string $dateFormat
    ): void {
        $sdkClient = $this->getSdkClient($this->getOrdersResponse());

        $result = $sdkClient->ordersV2()->getOrdersUpdatedBefore(
            $updatedBefore,
            OrderManager::DEFAULT_LIMIT,
            OrderManager::DEFAULT_OFFSET,
            $sortBy,
            $sortDirection,
            $dateFormat
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
        string $status,
        ?string $dateFormat
    ): void {
        $sdkClient = $this->getSdkClient($this->getOrdersResponse());

        $result = $sdkClient->ordersV2()->getOrdersWithStatus(
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

        $result = $sdkClient->ordersV2()->getOrdersWithStatus(
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
        string $status,
        ?string $shippingType = null
    ): void {
        $sdkClient = $this->getSdkClient($this->getOrdersResponse());

        $result = $sdkClient->ordersV2()->getOrdersFromParameters(
            $createdAfter,
            $createdBefore,
            $updatedAfter,
            $updatedBefore,
            $status,
            OrderManager::DEFAULT_LIMIT,
            OrderManager::DEFAULT_OFFSET,
            $sortBy,
            $sortDirection,
            null,
            $shippingType
        );

        $this->assertIsArray($result);
        $this->assertContainsOnlyInstancesOf(Order::class, $result);
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

        $sdkClient->ordersV2()->getOrdersWithStatus(
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

        $sdkClient->ordersV2()->getOrdersCreatedBetween(
            $since,
            $until,
            $limit,
            $offset,
            $sortBy,
            $sortDirection,
            null,
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

        $sdkClient->ordersV2()->getOrdersUpdatedBetween(
            $since,
            $until,
            $limit,
            $offset,
            $sortBy,
            $sortDirection,
            null,
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

        $sdkClient->ordersV2()->getOrdersCreatedBefore(
            $since,
            $limit,
            $offset,
            $sortBy,
            $sortDirection,
            null,
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

        $sdkClient->ordersV2()->getOrdersUpdatedBefore(
            $since,
            $limit,
            $offset,
            $sortBy,
            $sortDirection,
            null,
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

        $sdkClient->ordersV2()->getOrdersCreatedAfter(
            $since,
            $limit,
            $offset,
            $sortBy,
            $sortDirection,
            null,
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

        $sdkClient->ordersV2()->getOrdersUpdatedAfter(
            $since,
            $limit,
            $offset,
            $sortBy,
            $sortDirection,
            null,
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

        $sdkClient->ordersV2()->getOrdersFromParameters(
            null,
            null,
            null,
            null,
            'pending',
            $limit,
            $offset,
            $sortBy,
            $sortDirection,
            null,
            null,
            $debug
        );
    }

    public function dateTimesAndFilters(): array
    {
        $date = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2018-09-01 00:00:00');

        return [
            [$date, $date, 'created_at', 'ASC', 'pending', null],
            [$date, $date, 'updated_at', 'ASC', 'canceled', null],
            [$date, $date, 'created_at', 'DESC', 'ready_to_ship', null],
            [$date, $date, 'updated_at', 'DESC', 'delivered', null],
            [$date, $date, 'created_at', 'ASC', 'returned', null],
            [$date, $date, 'updated_at', 'DESC', 'shipped', null],
            [$date, $date, 'created_at', 'ASC', 'failed', null],
            [$date, $date, 'created_at', 'ASC', 'pending', 'Y-m-d\TH:i:s\Z'],
        ];
    }

    public function parametersFromGetOrders(): array
    {
        $date = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2018-09-01 00:00:00');

        return [
            [$date, $date, $date, $date, 'created_at', 'ASC', 'pending', null],
            [$date, $date, $date, $date, 'updated_at', 'ASC', 'canceled', null],
            [$date, $date, $date, $date, 'created_at', 'DESC', 'ready_to_ship', null],
            [$date, $date, $date, $date, 'updated_at', 'DESC', 'delivered', null],
            [$date, $date, $date, $date, 'created_at', 'ASC', 'returned', null],
            [$date, $date, $date, $date, 'updated_at', 'DESC', 'shipped', null],
            [$date, $date, $date, $date, 'created_at', 'ASC', 'failed', null],
            [null, $date, null, $date, 'updated_at', 'ASC', 'canceled', null],
            [$date, null, $date, null, 'invalid', 'DESC', 'ready_to_ship', null],
            [$date, $date, null, null, 'updated_at', 'invalid', 'delivered', null],
            [null, $date, $date, null, 'created_at', 'ASC', 'returned', null],
            [$date, null, null, $date, 'invalid', 'invalid', 'shipped', null],
            [null, null, null, null, 'created_at', 'ASC', 'failed', null],
            [null, null, null, null, 'created_at', 'ASC', 'pending', 'dropshipping'],
            [null, null, null, null, 'created_at', 'ASC', 'pending', 'own_warehouse'],
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
        return $this->getSchemaV2($schema);
    }
}

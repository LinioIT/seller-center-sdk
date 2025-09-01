<?php

declare(strict_types=1);

namespace Linio\SellerCenter\V2\Service;

use DateTimeInterface;
use Linio\SellerCenter\Application\Parameters;
use Linio\SellerCenter\Contract\OrderSortDirections;
use Linio\SellerCenter\Contract\OrderSortFilters;
use Linio\SellerCenter\Contract\OrderStatus;
use Linio\SellerCenter\Contract\ShippingTypeFilters;
use Linio\SellerCenter\Exception\InvalidDomainException;
use Linio\SellerCenter\Service\BaseManager;
use Linio\SellerCenter\V2\Factory\Xml\Order\OrderFactory;
use Linio\SellerCenter\V2\Factory\Xml\Order\OrdersFactory;
use Linio\SellerCenter\V2\Model\Order\Order;

class OrderManager extends BaseManager
{
    private const DEFAULT_LIMIT = 1000;
    private const DEFAULT_OFFSET = 0;
    private const DEFAULT_SORT_BY = 'created_at';
    private const DEFAULT_SORT_DIRECTION = 'ASC';
    private const DEFAULT_DATE_FORMAT = 'Y-m-d\TH:i:s';
    private const VERSION_2 = '2.0';

    public function getOrder(
        int $orderId,
        bool $debug = true
    ): Order {
        $action = 'GetOrder';

        $parameters = $this->makeParametersForAction($action, OrderManager::VERSION_2);

        $parameters->set([
            'OrderId' => $orderId,
        ]);

        $builtResponse = $this->executeAction(
            $action,
            $parameters,
            null,
            'GET',
            $debug
        );

        return OrderFactory::make($builtResponse->getBody()->Orders->Order);
    }

    /**
     * @return Order[]
     */
    protected function getOrders(
        Parameters $parameters,
        bool $debug = true
    ): array {
        $action = 'GetOrders';

        $requestId = $this->generateRequestId();

        $builtResponse = $this->executeAction(
            $action,
            $parameters,
            $requestId,
            'GET',
            $debug,
            null
        );

        $orders = OrdersFactory::make($builtResponse->getBody());

        $ordersResponse = array_values($orders->all());

        if ($debug) {
            $this->logger->info(
                sprintf(
                    '%s::%s::APIResponse::SellerCenterSdk: %d orders was recovered',
                    $requestId,
                    $action,
                    count($orders->all())
                )
            );
        }

        return $ordersResponse;
    }

    /**
     * @return Order[]
     */
    public function getOrdersCreatedBetween(
        DateTimeInterface $createdAfter,
        DateTimeInterface $createdBefore,
        int $limit = self::DEFAULT_LIMIT,
        int $offset = self::DEFAULT_OFFSET,
        string $sortBy = self::DEFAULT_SORT_BY,
        string $sortDirection = self::DEFAULT_SORT_DIRECTION,
        ?string $dateFormat = null,
        bool $debug = true
    ): array {
        $dateFormat = $dateFormat ?? self::DEFAULT_DATE_FORMAT;
        $parameters = $this->makeParametersForGetOrdersAction();

        $this->setListDimensions($parameters, $limit, $offset);
        $this->setSortParametersList($parameters, $sortBy, $sortDirection);

        $parameters->set([
            'CreatedAfter' => $createdAfter->format($dateFormat),
            'CreatedBefore' => $createdBefore->format($dateFormat),
        ]);

        return $this->getOrders(
            $parameters,
            $debug
        );
    }

    /**
     * @return Order[]
     */
    public function getOrdersUpdatedBetween(
        DateTimeInterface $updatedAfter,
        DateTimeInterface $updatedBefore,
        int $limit = self::DEFAULT_LIMIT,
        int $offset = self::DEFAULT_OFFSET,
        string $sortBy = self::DEFAULT_SORT_BY,
        string $sortDirection = self::DEFAULT_SORT_DIRECTION,
        ?string $dateFormat = null,
        bool $debug = true
    ): array {
        $parameters = $this->makeParametersForGetOrdersAction();
        $dateFormat = $dateFormat ?? self::DEFAULT_DATE_FORMAT;

        $this->setListDimensions($parameters, $limit, $offset);
        $this->setSortParametersList($parameters, $sortBy, $sortDirection);

        $parameters->set([
            'UpdatedAfter' => $updatedAfter->format($dateFormat),
            'UpdatedBefore' => $updatedBefore->format($dateFormat),
        ]);

        return $this->getOrders(
            $parameters,
            $debug
        );
    }

    /**
     * @return Order[]
     */
    public function getOrdersCreatedAfter(
        DateTimeInterface $createdAfter,
        int $limit = self::DEFAULT_LIMIT,
        int $offset = self::DEFAULT_OFFSET,
        string $sortBy = self::DEFAULT_SORT_BY,
        string $sortDirection = self::DEFAULT_SORT_DIRECTION,
        ?string $dateFormat = null,
        bool $debug = true
    ): array {
        $parameters = $this->makeParametersForGetOrdersAction();
        $dateFormat = $dateFormat ?? self::DEFAULT_DATE_FORMAT;

        $this->setListDimensions($parameters, $limit, $offset);
        $this->setSortParametersList($parameters, $sortBy, $sortDirection);

        $parameters->set([
            'CreatedAfter' => $createdAfter->format($dateFormat),
        ]);

        return $this->getOrders(
            $parameters,
            $debug
        );
    }

    /**
     * @return Order[]
     */
    public function getOrdersCreatedBefore(
        DateTimeInterface $createdBefore,
        int $limit = self::DEFAULT_LIMIT,
        int $offset = self::DEFAULT_OFFSET,
        string $sortBy = self::DEFAULT_SORT_BY,
        string $sortDirection = self::DEFAULT_SORT_DIRECTION,
        ?string $dateFormat = null,
        bool $debug = true
    ): array {
        $parameters = $this->makeParametersForGetOrdersAction();
        $dateFormat = $dateFormat ?? self::DEFAULT_DATE_FORMAT;

        $this->setListDimensions($parameters, $limit, $offset);
        $this->setSortParametersList($parameters, $sortBy, $sortDirection);

        $parameters->set([
            'CreatedBefore' => $createdBefore->format($dateFormat),
        ]);

        return $this->getOrders(
            $parameters,
            $debug
        );
    }

    /**
     * @return Order[]
     */
    public function getOrdersUpdatedAfter(
        DateTimeInterface $updatedAfter,
        int $limit = self::DEFAULT_LIMIT,
        int $offset = self::DEFAULT_OFFSET,
        string $sortBy = self::DEFAULT_SORT_BY,
        string $sortDirection = self::DEFAULT_SORT_DIRECTION,
        ?string $dateFormat = null,
        bool $debug = true
    ): array {
        $parameters = $this->makeParametersForGetOrdersAction();
        $dateFormat = $dateFormat ?? self::DEFAULT_DATE_FORMAT;

        $this->setListDimensions($parameters, $limit, $offset);
        $this->setSortParametersList($parameters, $sortBy, $sortDirection);

        $parameters->set([
            'UpdatedAfter' => $updatedAfter->format($dateFormat),
        ]);

        return $this->getOrders(
            $parameters,
            $debug
        );
    }

    /**
     * @return Order[]
     */
    public function getOrdersUpdatedBefore(
        DateTimeInterface $updatedBefore,
        int $limit = self::DEFAULT_LIMIT,
        int $offset = self::DEFAULT_OFFSET,
        string $sortBy = self::DEFAULT_SORT_BY,
        string $sortDirection = self::DEFAULT_SORT_DIRECTION,
        ?string $dateFormat = null,
        bool $debug = true
    ): array {
        $parameters = $this->makeParametersForGetOrdersAction();
        $dateFormat = $dateFormat ?? self::DEFAULT_DATE_FORMAT;

        $this->setListDimensions($parameters, $limit, $offset);
        $this->setSortParametersList($parameters, $sortBy, $sortDirection);

        $parameters->set([
            'UpdatedBefore' => $updatedBefore->format($dateFormat),
        ]);

        return $this->getOrders(
            $parameters,
            $debug
        );
    }

    /**
     * @return Order[]
     */
    public function getOrdersWithStatus(
        string $status,
        int $limit = self::DEFAULT_LIMIT,
        int $offset = self::DEFAULT_OFFSET,
        string $sortBy = self::DEFAULT_SORT_BY,
        string $sortDirection = self::DEFAULT_SORT_DIRECTION,
        bool $debug = true
    ): array {
        $parameters = $this->makeParametersForGetOrdersAction();

        $this->setListDimensions($parameters, $limit, $offset);
        $this->setSortParametersList($parameters, $sortBy, $sortDirection);

        if (!in_array($status, OrderStatus::STATUS)) {
            throw new InvalidDomainException('Status');
        }

        $parameters->set([
            'Status' => $status,
        ]);

        return $this->getOrders(
            $parameters,
            $debug
        );
    }

    /**
     * @return Order[]
     */
    public function getOrdersFromParameters(
        ?DateTimeInterface $createdAfter = null,
        ?DateTimeInterface $createdBefore = null,
        ?DateTimeInterface $updatedAfter = null,
        ?DateTimeInterface $updatedBefore = null,
        ?string $status = null,
        int $limit = self::DEFAULT_LIMIT,
        int $offset = self::DEFAULT_OFFSET,
        string $sortBy = self::DEFAULT_SORT_BY,
        string $sortDirection = self::DEFAULT_SORT_DIRECTION,
        ?string $dateFormat = null,
        ?string $shippingType = null,
        bool $debug = true
    ): array {
        $parameters = $this->makeParametersForGetOrdersAction();
        $dateFormat = $dateFormat ?? self::DEFAULT_DATE_FORMAT;

        $this->setListDimensions($parameters, $limit, $offset);
        $this->setSortParametersList($parameters, $sortBy, $sortDirection);

        if (!empty($createdAfter)) {
            $parameters->set(['CreatedAfter' => $createdAfter->format($dateFormat)]);
        }

        if (!empty($createdBefore)) {
            $parameters->set(['CreatedBefore' => $createdBefore->format($dateFormat)]);
        }

        if (!empty($updatedAfter)) {
            $parameters->set(['UpdatedAfter' => $updatedAfter->format($dateFormat)]);
        }

        if (!empty($updatedBefore)) {
            $parameters->set(['UpdatedBefore' => $updatedBefore->format($dateFormat)]);
        }

        if (!empty($status) && in_array($status, OrderStatus::STATUS)) {
            $parameters->set(['Status' => $status]);
        }

        if (!empty($shippingType) && in_array($shippingType, ShippingTypeFilters::SHIPPING_TYPES)) {
            $parameters->set(['ShippingType' => $shippingType]);
        }

        return $this->getOrders(
            $parameters,
            $debug
        );
    }

    protected function setListDimensions(Parameters &$parameters, int $limit, int $offset): void
    {
        $verifiedLimit = $limit >= 1 ? $limit : self::DEFAULT_LIMIT;
        $verifiedOffset = $offset < 0 ? self::DEFAULT_OFFSET : $offset;

        $parameters->set(
            [
                'Limit' => $verifiedLimit,
                'Offset' => $verifiedOffset,
            ]
        );
    }

    protected function setSortParametersList(Parameters &$parameters, string $sortBy, string $sortDirection): void
    {
        if (!in_array($sortBy, OrderSortFilters::SORT_FILTERS)) {
            $sortBy = self::DEFAULT_SORT_BY;
        }

        if (!in_array($sortDirection, OrderSortDirections::SORT_DIRECTIONS)) {
            $sortDirection = self::DEFAULT_SORT_DIRECTION;
        }

        $parameters->set([
            'SortBy' => $sortBy,
            'SortDirection' => $sortDirection,
        ]);
    }

    protected function makeParametersForGetOrdersAction(): Parameters
    {
        return $this->makeParametersForAction('GetOrders', self::VERSION_2);
    }
}

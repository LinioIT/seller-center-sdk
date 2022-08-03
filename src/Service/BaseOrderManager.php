<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Service;

use DateTimeInterface;
use Linio\Component\Util\Json;
use Linio\SellerCenter\Application\Parameters;
use Linio\SellerCenter\Application\Security\Signature;
use Linio\SellerCenter\Contract\OrderSortDirections;
use Linio\SellerCenter\Contract\OrderSortFilters;
use Linio\SellerCenter\Contract\OrderStatus;
use Linio\SellerCenter\Exception\EmptyArgumentException;
use Linio\SellerCenter\Exception\InvalidDomainException;
use Linio\SellerCenter\Factory\RequestFactory;
use Linio\SellerCenter\Factory\Xml\Order\FailureReasonsFactory;
use Linio\SellerCenter\Factory\Xml\Order\OrderFactory;
use Linio\SellerCenter\Factory\Xml\Order\OrderItemsFactory;
use Linio\SellerCenter\Factory\Xml\Order\OrdersFactory;
use Linio\SellerCenter\Factory\Xml\Order\OrdersItemsFactory;
use Linio\SellerCenter\Factory\Xml\Order\TrackingCodeFactory;
use Linio\SellerCenter\Formatter\LogMessageFormatter;
use Linio\SellerCenter\Model\Order\FailureReason;
use Linio\SellerCenter\Model\Order\Order;
use Linio\SellerCenter\Model\Order\OrderItem;
use Linio\SellerCenter\Model\Order\TrackingCode;
use Linio\SellerCenter\Response\HandleResponse;
use Linio\SellerCenter\Response\SuccessResponse;

class BaseOrderManager extends BaseManager
{
    public const DEFAULT_LIMIT = 1000;
    public const DEFAULT_OFFSET = 0;
    public const DEFAULT_SORT_BY = 'created_at';
    public const DEFAULT_SORT_DIRECTION = 'ASC';

    public function getOrder(int $orderId): Order
    {
        $action = 'GetOrder';

        $parameters = clone $this->parameters;
        $parameters->set([
            'Action' => $action,
            'OrderId' => $orderId,
        ]);
        $parameters->set([
            'Signature' => Signature::generate($parameters, $this->configuration->getKey())->get(),
        ]);

        $requestHeaders = $this->generateRequestHeaders();
        $requestId = $requestHeaders[self::REQUEST_ID_HEADER];

        $request = RequestFactory::make(
            'GET',
            $this->configuration->getEndpoint(),
            $requestHeaders
        );

        $this->logger->debug(
            LogMessageFormatter::fromAction($requestId, $action, LogMessageFormatter::TYPE_REQUEST),
            [
                'url' => (string) $request->getUri(),
                'method' => $request->getMethod(),
                'body' => (string) $request->getBody(),
                'parameters' => $parameters->all(),
            ]
        );

        $response = $this->client->send($request, [
            'query' => $parameters->all(),
        ]);

        $body = (string) $response->getBody();

        $this->logger->debug(
            LogMessageFormatter::fromAction($requestId, $action, LogMessageFormatter::TYPE_RESPONSE),
            [
                'body' => $body,
            ]
        );

        $builtResponse = HandleResponse::parse($body);

        $this->logger->debug(
            LogMessageFormatter::fromAction($requestId, $action, LogMessageFormatter::TYPE_BUILT_RESPONSE),
            [
                'head' => $builtResponse->getHead()->asXML(),
                'body' => $builtResponse->getBody()->asXML(),
            ]
        );

        $orderResponse = OrderFactory::make($builtResponse->getBody()->Orders->Order);

        $this->logger->info(
            sprintf(
                '%d::%s::APIResponse::SellerCenterSdk: the order was recovered',
                $request->getHeaderLine('Request-ID'),
                $action
            )
        );

        return $orderResponse;
    }

    /**
     * @return OrderItem[]
     */
    public function getOrderItems(int $orderId): array
    {
        $action = 'GetOrderItems';

        $parameters = $this->makeParametersForAction($action);
        $parameters->set([
            'OrderId' => $orderId,
        ]);

        $requestId = $this->generateRequestId();

        $builtResponse = $this->executeAction(
            $action,
            $parameters,
            $requestId,
            'GET'
        );

        $orderItems = OrderItemsFactory::make($builtResponse->getBody());

        $orderItemsResponse = array_values($orderItems->all());

        $this->logger->info(
            sprintf(
                '%d::%s::APIResponse::SellerCenterSdk: %d order items was recovered',
                $requestId,
                $action,
                count($orderItems->all())
            )
        );

        return $orderItemsResponse;
    }

    /**
     * @param mixed[] $orderIdList
     *
     * @return Order[]
     */
    public function getMultipleOrderItems(array $orderIdList): array
    {
        $action = 'GetMultipleOrderItems';

        $parameters = $this->makeParametersForAction($action);

        if (empty($orderIdList)) {
            throw new EmptyArgumentException('OrderIdList');
        }
        $parameters->set([
            'OrderIdList' => Json::encode($orderIdList),
        ]);

        $requestId = $this->generateRequestId();

        $builtResponse = $this->executeAction(
            $action,
            $parameters,
            $requestId,
            'GET'
        );

        $orderItems = OrdersItemsFactory::make($builtResponse->getBody());

        $multipleOrderItemsResponse = array_values($orderItems->all());

        $this->logger->info(
            sprintf(
                '%d::%s::APIResponse::SellerCenterSdk: %d orders items was recovered',
                $requestId,
                $action,
                count($orderItems->all())
            )
        );

        return $multipleOrderItemsResponse;
    }

    /**
     * @return Order[]
     */
    protected function getOrders(Parameters $parameters): array
    {
        $action = 'GetOrders';
        $parameters = $this->makeParametersForAction($action);

        $requestId = $this->generateRequestId();

        $builtResponse = $this->executeAction(
            $action,
            $parameters,
            $requestId,
            'GET'
        );

        $orders = OrdersFactory::make($builtResponse->getBody());

        $ordersResponse = array_values($orders->all());

        $this->logger->info(
            sprintf(
                '%d::%s::APIResponse::SellerCenterSdk: %d orders was recovered',
                $requestId,
                $action,
                count($orders->all())
            )
        );

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
        string $sortDirection = self::DEFAULT_SORT_DIRECTION
    ): array {
        $parameters = clone $this->parameters;

        $this->setListDimensions($parameters, $limit, $offset);
        $this->setSortParametersList($parameters, $sortBy, $sortDirection);

        $parameters->set([
            'CreatedAfter' => $createdAfter->format('Y-m-d\TH:i:s'),
            'CreatedBefore' => $createdBefore->format('Y-m-d\TH:i:s'),
        ]);

        return $this->getOrders($parameters);
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
        string $sortDirection = self::DEFAULT_SORT_DIRECTION
    ): array {
        $parameters = clone $this->parameters;

        $this->setListDimensions($parameters, $limit, $offset);
        $this->setSortParametersList($parameters, $sortBy, $sortDirection);

        $parameters->set([
            'UpdatedAfter' => $updatedAfter->format('Y-m-d\TH:i:s'),
            'UpdatedBefore' => $updatedBefore->format('Y-m-d\TH:i:s'),
        ]);

        return $this->getOrders($parameters);
    }

    /**
     * @return Order[]
     */
    public function getOrdersCreatedAfter(
        DateTimeInterface $createdAfter,
        int $limit = self::DEFAULT_LIMIT,
        int $offset = self::DEFAULT_OFFSET,
        string $sortBy = self::DEFAULT_SORT_BY,
        string $sortDirection = self::DEFAULT_SORT_DIRECTION
    ): array {
        $parameters = clone $this->parameters;

        $this->setListDimensions($parameters, $limit, $offset);
        $this->setSortParametersList($parameters, $sortBy, $sortDirection);

        $parameters->set([
            'CreatedAfter' => $createdAfter->format('Y-m-d\TH:i:s'),
        ]);

        return $this->getOrders($parameters);
    }

    /**
     * @return Order[]
     */
    public function getOrdersCreatedBefore(
        DateTimeInterface $createdBefore,
        int $limit = self::DEFAULT_LIMIT,
        int $offset = self::DEFAULT_OFFSET,
        string $sortBy = self::DEFAULT_SORT_BY,
        string $sortDirection = self::DEFAULT_SORT_DIRECTION
    ): array {
        $parameters = clone $this->parameters;

        $this->setListDimensions($parameters, $limit, $offset);
        $this->setSortParametersList($parameters, $sortBy, $sortDirection);

        $parameters->set([
            'CreatedBefore' => $createdBefore->format('Y-m-d\TH:i:s'),
        ]);

        return $this->getOrders($parameters);
    }

    /**
     * @return Order[]
     */
    public function getOrdersUpdatedAfter(
        DateTimeInterface $updatedAfter,
        int $limit = self::DEFAULT_LIMIT,
        int $offset = self::DEFAULT_OFFSET,
        string $sortBy = self::DEFAULT_SORT_BY,
        string $sortDirection = self::DEFAULT_SORT_DIRECTION
    ): array {
        $parameters = clone $this->parameters;

        $this->setListDimensions($parameters, $limit, $offset);
        $this->setSortParametersList($parameters, $sortBy, $sortDirection);

        $parameters->set([
            'UpdatedAfter' => $updatedAfter->format('Y-m-d\TH:i:s'),
        ]);

        return $this->getOrders($parameters);
    }

    /**
     * @return Order[]
     */
    public function getOrdersUpdatedBefore(
        DateTimeInterface $updatedBefore,
        int $limit = self::DEFAULT_LIMIT,
        int $offset = self::DEFAULT_OFFSET,
        string $sortBy = self::DEFAULT_SORT_BY,
        string $sortDirection = self::DEFAULT_SORT_DIRECTION
    ): array {
        $parameters = clone $this->parameters;

        $this->setListDimensions($parameters, $limit, $offset);
        $this->setSortParametersList($parameters, $sortBy, $sortDirection);

        $parameters->set([
            'UpdatedBefore' => $updatedBefore->format('Y-m-d\TH:i:s'),
        ]);

        return $this->getOrders($parameters);
    }

    /**
     * @return Order[]
     */
    public function getOrdersWithStatus(
        string $status,
        int $limit = self::DEFAULT_LIMIT,
        int $offset = self::DEFAULT_OFFSET,
        string $sortBy = self::DEFAULT_SORT_BY,
        string $sortDirection = self::DEFAULT_SORT_DIRECTION
    ): array {
        $parameters = clone $this->parameters;

        $this->setListDimensions($parameters, $limit, $offset);
        $this->setSortParametersList($parameters, $sortBy, $sortDirection);

        if (!in_array($status, OrderStatus::STATUS)) {
            throw new InvalidDomainException('Status');
        }

        $parameters->set([
            'Status' => $status,
        ]);

        return $this->getOrders($parameters);
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
        string $sortDirection = self::DEFAULT_SORT_DIRECTION
    ): array {
        $parameters = clone $this->parameters;

        $this->setListDimensions($parameters, $limit, $offset);
        $this->setSortParametersList($parameters, $sortBy, $sortDirection);

        if (!empty($createdAfter)) {
            $parameters->set(['CreatedAfter' => $createdAfter->format('Y-m-d\TH:i:s')]);
        }

        if (!empty($createdBefore)) {
            $parameters->set(['CreatedBefore' => $createdBefore->format('Y-m-d\TH:i:s')]);
        }

        if (!empty($updatedAfter)) {
            $parameters->set(['UpdatedAfter' => $updatedAfter->format('Y-m-d\TH:i:s')]);
        }

        if (!empty($updatedBefore)) {
            $parameters->set(['UpdatedBefore' => $updatedBefore->format('Y-m-d\TH:i:s')]);
        }

        if (!empty($status) && in_array($status, OrderStatus::STATUS)) {
            $parameters->set(['Status' => $status]);
        }

        return $this->getOrders($parameters);
    }

    /**
     * @param mixed[] $orderItemIds
     *
     * @return OrderItem[]
     */
    public function setStatusToPackedByMarketplace(
        array $orderItemIds,
        string $deliveryType,
        string $shippingProvider = null,
        string $trackingNumber = null
    ): array {
        $action = 'SetStatusToPackedByMarketplace';

        $parameters = $this->makeParametersForAction($action);
        $parameters->set([
            'OrderItemIds' => Json::encode($orderItemIds),
            'DeliveryType' => $deliveryType,
        ]);

        if (!empty($shippingProvider)) {
            $parameters->set(['ShippingProvider' => $shippingProvider]);
        }

        if (!empty($trackingNumber)) {
            $parameters->set(['TrackingNumber' => $trackingNumber]);
        }

        $requestId = $this->generateRequestId();

        $builtResponse = $this->executeAction(
            $action,
            $parameters,
            $requestId,
            'POST'
        );

        $orderItems = OrderItemsFactory::makeFromStatus($builtResponse->getBody());

        $orderItemsResponse = array_values($orderItems->all());

        $this->logger->info(
            sprintf(
                '%d::%s::APIResponse::SellerCenterSdk: the items status was changed',
                $requestId,
                $action
            )
        );

        return $orderItemsResponse;
    }

    /**
     * @param mixed[] $orderItemIds
     *
     * @return OrderItem[]
     */
    public function setStatusToReadyToShip(
        array $orderItemIds,
        string $deliveryType,
        string $shippingProvider = null,
        string $trackingNumber = null,
        ?string $packageId = null
    ): array {
        $action = 'SetStatusToReadyToShip';

        $parameters = $this->makeParametersForAction($action);
        $parameters->set([
            'OrderItemIds' => Json::encode($orderItemIds),
            'DeliveryType' => $deliveryType,
        ]);

        if (!empty($shippingProvider)) {
            $parameters->set(['ShippingProvider' => $shippingProvider]);
        }

        if (!empty($trackingNumber)) {
            $parameters->set(['TrackingNumber' => $trackingNumber]);
        }

        if (!empty($packageId)) {
            $parameters->set(['PackageId' => $packageId]);
        }

        $requestId = $this->generateRequestId();

        $builtResponse = $this->executeAction(
            $action,
            $parameters,
            $requestId,
            'POST'
        );

        $orderItems = OrderItemsFactory::makeFromStatus($builtResponse->getBody());

        $orderItemsResponse = array_values($orderItems->all());

        $this->logger->info(
            sprintf(
                '%d::%s::APIResponse::SellerCenterSdk: the items status was changed',
                $requestId,
                $action
            )
        );

        return $orderItemsResponse;
    }

    public function getTrackingCode(
        string $packageId,
        string $shippingProvider
    ): TrackingCode {
        $action = 'GetTrackingCode';
        $parameters = $this->makeParametersForAction($action);

        $parameters->set([
            'packageId' => $packageId,
            'shipping_provider_name' => $shippingProvider,
        ]);

        $requestId = $this->generateRequestId();
        $response = $this->executeAction(
            $action,
            $parameters,
            $requestId,
            'GET'
        );

        $this->logger->info(
            sprintf(
                '%d::%s::APIResponse::SellerCenterSdk: Get Tracking Code',
                $requestId,
                $action
            )
        );

        return TrackingCodeFactory::make($response->getBody());
    }

    public function setStatusToCanceled(
        int $orderItemId, 
        string $reason, 
        string $reasonDetail = null
        ): SuccessResponse {
        $action = 'SetStatusToCanceled';

        $parameters = $this->makeParametersForAction($action);
        $parameters->set([
            'OrderItemId' => $orderItemId,
            'Reason' => $reason,
        ]);

        if (!empty($reasonDetail)) {
            $parameters->set(['ReasonDetail' => $reasonDetail]);
        }

        $requestId = $this->generateRequestId();

        $response = $this->executeAction(
            $action,
            $parameters,
            $requestId,
            'POST'
        );

            $this->logger->info(
            sprintf(
                '%d::%s::APIResponse::SellerCenterSdk: the items status was changed',
                $requestId,
                $action
            )
        );

        return $response;
    }

    /**
     * @return FailureReason[]
     */
    public function getFailureReasons(): array
    {
        $action = 'GetFailureReasons';

        $parameters = $this->makeParametersForAction($action);

        $requestId = $this->generateRequestId();

        $builtResponse = $this->executeAction(
            $action,
            $parameters,
            $requestId,
            'POST'
        );

        $reasons = FailureReasonsFactory::make($builtResponse->getBody());

        $reasonsResponse = $reasons->all();

        $this->logger->info(
            sprintf(
                '%d::%s::APIResponse::SellerCenterSdk: %d failure reasons was recovered',
                $requestId,
                $action,
                count($reasons->all())
            )
        );

        return $reasonsResponse;
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
}

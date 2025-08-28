<?php

declare(strict_types=1);

namespace Linio\SellerCenter\V2\Service;

use Linio\Component\Util\Json;
use Linio\SellerCenter\Application\Parameters;
use Linio\SellerCenter\Factory\Xml\Order\OrderItemsFactory;
use Linio\SellerCenter\Factory\Xml\V2\Order\OrderFactory;
use Linio\SellerCenter\Factory\Xml\V2\Order\OrdersFactory;
use Linio\SellerCenter\Model\Order\OrderItem;
use Linio\SellerCenter\Service\BaseManager;
use Linio\SellerCenter\V2\Model\Order\Order;

class GlobalOrderManager extends BaseManager
{
    private const VERSION_2 = '2.0';
    const ALLOWED_INVOICE_TYPE = [
        'BOLETA',
        'NOTA_DE_CREDITO',
    ];

    public function getOrder(
        int $orderId,
        bool $debug = true
    ): Order {
        $action = 'GetOrder';

        $parameters = $this->makeParametersForAction($action);

        $parameters->set([
            'OrderId' => $orderId,
        ]);

        $builtResponse = $this->executeAction(
            $action,
            $parameters,
            null,
            'GET',
            $debug,
            null,
            self::VERSION_2
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
            null,
            self::VERSION_2
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
     * @param mixed[] $orderItemIds
     * @param string[] $packageIds
     *
     * @return OrderItem[]
     */
    public function setStatusToReadyToShip(
        array $orderItemIds,
        array $packageIds,
        bool $debug = true
    ): array {
        $action = 'SetStatusToReadyToShip';

        $parameters = $this->makeParametersForAction($action);

        $parameters->set([
            'OrderItemIds' => Json::encode($orderItemIds),
        ]);

        if (!empty($packageIds)) {
            $parameters->set(['PackageIds' => Json::encode($packageIds)]);
        }

        $builtResponse = $this->executeAction(
            $action,
            $parameters,
            null,
            'POST',
            $debug,
            null,
            self::VERSION_2
        );

        $orderItems = OrderItemsFactory::makeFromStatus($builtResponse->getBody());

        return array_values($orderItems->all());
    }
}

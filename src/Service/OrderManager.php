<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Service;

use Linio\SellerCenter\Factory\Xml\Order\OrderItemsFactory;
use Linio\SellerCenter\Model\Order\OrderItem;
use Linio\SellerCenter\Transformer\Order\OrderItemsTransformer;

class OrderManager extends BaseOrderManager
{
    /**
     * @param OrderItem[] $orderItems
     *
     * @return OrderItem[]
     */
    public function setOrderItemsImei(
        array $orderItems
    ): array {
        $action = 'SetImei';
        $parameters = $this->makeParametersForAction($action);
        $requestId = $this->generateRequestId();

        $builtResponse = $this->executeAction(
            $action,
            $parameters,
            $requestId,
            'POST',
            OrderItemsTransformer::orderItemsImeiAsXmlString($orderItems)
        );

        $orderItems = OrderItemsFactory::makeFromImeiStatus($builtResponse->getBody());

        $this->logger->info(
            sprintf(
                '%d::%s::APIResponse::SellerCenterSdk: Imei Set',
                $requestId,
                $action
            )
        );

        return $orderItems;
    }
}

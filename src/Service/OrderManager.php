<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Service;

use Linio\SellerCenter\Model\Order\OrderItems;
use Linio\SellerCenter\Response\SuccessResponse;
use Linio\SellerCenter\Transformer\Order\OrderItemsTransformer;

class OrderManager extends BaseOrderManager
{
    public function setOrderItemsImei(
        OrderItems $orderItems
    ): SuccessResponse {
        $action = 'SetImei';
        $parameters = $this->makeParametersForAction($action);
        $requestId = $this->generateRequestId();

        $response = $this->executeAction(
            $action,
            $parameters,
            $requestId,
            'POST',
            OrderItemsTransformer::orderItemsImeiAsXmlString($orderItems)
        );

        $this->logger->info(
            sprintf(
                '%d::%s::APIResponse::SellerCenterSdk: Imei Set',
                $requestId,
                $action
            )
        );

        return $response;
    }
}

<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Service;

use Linio\SellerCenter\Factory\Xml\Order\OrderItemsFactory;
use Linio\SellerCenter\Model\Order\OrderItem;
use Linio\SellerCenter\Transformer\Order\OrderItemsTransformer;
use Linio\SellerCenter\Response\SuccessResponse;

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

    public function setInvoiceNumber(
        int $orderItemId,
        string $invoiceNumber
    ): SuccessResponse {
        $action = 'SetInvoiceNumber';
        $parameters = $this->makeParametersForAction($action);

        $parameters->set([
            'OrderItemId' => $orderItemId,
            'InvoiceNumber' => $invoiceNumber,
        ]);

        $requestId = $this->generateRequestId();
        $response = $this->executeAction(
            $action,
            $parameters,
            $requestId,
            'POST'
        );

        $this->logger->info(
            sprintf(
                '%d::%s::APIResponse::SellerCenterSdk: Invoice Number Set',
                $requestId,
                $action
            )
        );

        return $response;
    }
}

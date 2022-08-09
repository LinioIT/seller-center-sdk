<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Service;

use Linio\Component\Util\Json;
use Linio\SellerCenter\Factory\Xml\Order\OrderItemsFactory;
use Linio\SellerCenter\Model\Order\OrderItem;
use Linio\SellerCenter\Response\SuccessResponse;
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

    /**
     * @param mixed[] $orderItemIds
     *
     * @return OrderItem[]
     */
    public function setStatusToReadyToShip(
        array $orderItemIds,
        string $deliveryType,
        string $shippingProvider = null,
        string $trackingNumber = null
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
}

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
        array $orderItems,
        bool $debug = true
    ): array {
        $action = 'SetImei';
        $parameters = $this->makeParametersForAction($action);
        $requestId = $this->generateRequestId();

        $builtResponse = $this->executeAction(
            $action,
            $parameters,
            $requestId,
            'POST',
            $debug,
            OrderItemsTransformer::orderItemsImeiAsXmlString($orderItems)
        );

        return OrderItemsFactory::makeFromImeiStatus($builtResponse->getBody());
    }

    public function setInvoiceNumber(
        int $orderItemId,
        string $invoiceNumber,
        bool $debug = true
    ): SuccessResponse {
        $action = 'SetInvoiceNumber';
        $parameters = $this->makeParametersForAction($action);

        $parameters->set([
            'OrderItemId' => $orderItemId,
            'InvoiceNumber' => $invoiceNumber,
        ]);

        return $this->executeAction(
            $action,
            $parameters,
            null,
            'POST',
            $debug
        );
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
        bool $debug = true
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

        $builtResponse = $this->executeAction(
            $action,
            $parameters,
            null,
            'POST',
            $debug
        );

        $orderItems = OrderItemsFactory::makeFromStatus($builtResponse->getBody());

        return array_values($orderItems->all());
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
        string $trackingNumber = null,
        bool $debug = true
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

        $builtResponse = $this->executeAction(
            $action,
            $parameters,
            null,
            'POST',
            $debug
        );

        $orderItems = OrderItemsFactory::makeFromStatus($builtResponse->getBody());

        return array_values($orderItems->all());
    }
}

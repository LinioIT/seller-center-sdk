<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Service;

use Linio\Component\Util\Json;
use Linio\SellerCenter\Factory\Xml\FeedResponseFactory;
use Linio\SellerCenter\Factory\Xml\Order\OrderItemsFactory;
use Linio\SellerCenter\Model\Order\OrderItem;
use Linio\SellerCenter\Response\FeedResponse;
use Linio\SellerCenter\Response\SuccessResponse;

class GlobalOrderManager extends BaseOrderManager
{
    public function setInvoiceNumber(
        int $orderItemId,
        string $invoiceNumber,
        ?string $invoiceDocumentLink,
        bool $debug = true
    ): SuccessResponse {
        $action = 'SetInvoiceNumber';

        $parameters = $this->makeParametersForAction($action);

        $parameters->set([
            'OrderItemId' => $orderItemId,
            'InvoiceNumber' => $invoiceNumber,
        ]);

        if (!empty($invoiceDocumentLink)) {
            $parameters->set(['InvoiceDocumentLink' => $invoiceDocumentLink]);
        }

        return $this->executeAction(
            $action,
            $parameters,
            null,
            'POST',
            $debug
        );
    }

    public function setInvoiceDocument(
        int $orderItemId,
        string $invoiceNumber,
        string $invoiceDocument,
        bool $debug = true
    ): FeedResponse {
        $action = 'SetInvoiceDocument';

        $parameters = $this->makeParametersForAction($action);

        $parameters->set([
            'OrderItemId' => $orderItemId,
            'InvoiceNumber' => $invoiceNumber,
        ]);

        $response = $this->executeAction(
            $action,
            $parameters,
            null,
            'POST',
            $debug,
            $invoiceDocument
        );

        return FeedResponseFactory::make($response->getHead());
    }

    /**
     * @param mixed[] $orderItemIds
     *
     * @return OrderItem[]
     */
    public function setStatusToReadyToShip(
        array $orderItemIds,
        string $deliveryType,
        ?string $packageId = null,
        bool $debug = true
    ): array {
        $action = 'SetStatusToReadyToShip';

        $parameters = $this->makeParametersForAction($action);

        $parameters->set([
            'OrderItemIds' => Json::encode($orderItemIds),
            'DeliveryType' => $deliveryType,
        ]);

        if (!empty($packageId)) {
            $parameters->set(['PackageId' => $packageId]);
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
        bool $debug = true
    ): array {
        $action = 'SetStatusToPackedByMarketplace';

        $parameters = $this->makeParametersForAction($action);

        $parameters->set([
            'OrderItemIds' => Json::encode($orderItemIds),
            'DeliveryType' => $deliveryType,
        ]);

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

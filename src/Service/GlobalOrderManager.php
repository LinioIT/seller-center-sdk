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
        ?string $invoiceDocumentLink
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

    public function setInvoiceDocument(
        int $orderItemId,
        string $invoiceNumber,
        string $invoiceDocument
    ): FeedResponse {
        $action = 'SetInvoiceDocument';

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
            'POST',
            $invoiceDocument
        );

        $feedResponse = FeedResponseFactory::make($response->getHead());

        $this->logger->info(
            sprintf(
                '%d::%s::APIResponse::SellerCenterSdk: Invoice Document Set',
                $requestId,
                $action
            )
        );

        return $feedResponse;
    }

    /**
     * @param mixed[] $orderItemIds
     *
     * @return OrderItem[]
     */
    public function setStatusToReadyToShip(
        array $orderItemIds,
        string $deliveryType,
        ?string $packageId = null
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
        string $deliveryType
    ): array {
        $action = 'SetStatusToPackedByMarketplace';

        $parameters = $this->makeParametersForAction($action);

        $parameters->set([
            'OrderItemIds' => Json::encode($orderItemIds),
            'DeliveryType' => $deliveryType,
        ]);

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

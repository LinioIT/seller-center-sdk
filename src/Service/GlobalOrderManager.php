<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Service;

use Linio\Component\Util\Json;
use Linio\SellerCenter\Application\Parameters;
use Linio\SellerCenter\Exception\InvalidDomainException;
use Linio\SellerCenter\Factory\Xml\FeedResponseFactory;
use Linio\SellerCenter\Factory\Xml\Order\OrderItemsFactory;
use Linio\SellerCenter\Model\Order\InvoiceDocument;
use Linio\SellerCenter\Model\Order\OrderItem;
use Linio\SellerCenter\Response\FeedResponse;
use Linio\SellerCenter\Response\SuccessJsonResponse;
use Linio\SellerCenter\Response\SuccessResponse;

class GlobalOrderManager extends BaseOrderManager
{
    const ALLOWED_INVOICE_TYPE = [
        'BOLETA',
        'NOTA_DE_CREDITO',
    ];

    /**
     * @param int[] $orderItemIds
     */
    public function setInvoiceNumber(
        array $orderItemIds,
        string $invoiceNumber,
        ?string $invoiceDocumentLink,
        bool $debug = true
    ): SuccessResponse {
        $action = 'SetInvoiceNumber';

        $parameters = $this->makeParametersForAction($action);

        $parameters->set([
            'OrderItemIds' => Json::encode($orderItemIds),
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

    /**
     * @param int[] $orderItemIds
     */
    public function setInvoiceDocument(
        array $orderItemIds,
        string $invoiceNumber,
        string $invoiceType,
        string $invoiceDocument,
        bool $debug = true
    ): FeedResponse {
        $upperInvoiceType = strtoupper($invoiceType);

        if (!in_array(strtoupper($upperInvoiceType), self::ALLOWED_INVOICE_TYPE)) {
            throw new InvalidDomainException('InvoiceType');
        }

        $action = 'SetInvoiceDocument';

        $parameters = $this->makeParametersForAction($action);

        $parameters->set([
            'OrderItemIds' => Json::encode($orderItemIds),
            'InvoiceNumber' => $invoiceNumber,
            'InvoiceType' => $upperInvoiceType,
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

    public function uploadInvoiceDocument(
        InvoiceDocument $invoiceDocument,
        bool $debug = true
    ): SuccessJsonResponse {
        $action = 'SetInvoicePDF';
        $path = '/seller-api-wrapper/v1/marketplace-sellers/invoice/pdf';
        $customHeader = ['Service' => 'Invoice'];

        $invoiceDocumentFormatted = Json::encode($invoiceDocument->jsonSerialize());

        return $this->executeJsonAction(
            $action,
            new Parameters(),
            null,
            'POST',
            $debug,
            $invoiceDocumentFormatted,
            true,
            $customHeader,
            $path
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

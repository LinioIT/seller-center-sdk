<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Service;

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
}

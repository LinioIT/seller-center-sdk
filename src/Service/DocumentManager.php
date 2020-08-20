<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Service;

use Linio\Component\Util\Json;
use Linio\SellerCenter\Factory\Xml\Document\DocumentFactory;
use Linio\SellerCenter\Model\Document\Document;

class DocumentManager extends BaseManager
{
    private const GET_DOCUMENT_ACTION = 'GetDocument';

    public function getDocument(string $documentType, array $orderItemIds): Document
    {
        $action = self::GET_DOCUMENT_ACTION;

        $parameters = clone $this->parameters;
        $parameters->set([
            'DocumentType' => $documentType,
            'OrderItemIds' => Json::encode($orderItemIds),
        ]);

        $requestId = $this->generateRequestId();

        $builtResponse = $this->executeAction($action, $requestId);

        $documentResponse = DocumentFactory::make($builtResponse->getBody()->Documents->Document);

        $this->logger->info(
            sprintf(
                '%d::%s::APIResponse::SellerCenterSdk: the document was recovered',
                $requestId,
                $action
            )
        );

        return $documentResponse;
    }
}

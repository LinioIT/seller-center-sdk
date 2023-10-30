<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Service;

use Linio\Component\Util\Json;
use Linio\SellerCenter\Factory\Xml\Document\DocumentFactory;
use Linio\SellerCenter\Model\Document\Document;

class DocumentManager extends BaseManager
{
    /**
     * @param mixed[] $orderItemIds
     */
    public function getDocument(
        string $documentType,
        array $orderItemIds,
        bool $debug = true
    ): Document {
        $action = 'GetDocument';

        $parameters = $this->makeParametersForAction($action);
        $parameters->set([
            'DocumentType' => $documentType,
            'OrderItemIds' => Json::encode($orderItemIds),
        ]);

        $builtResponse = $this->executeAction(
            $action,
            $parameters,
            null,
            'GET',
            $debug
        );

        return DocumentFactory::make($builtResponse->getBody()->Documents->Document);
    }
}

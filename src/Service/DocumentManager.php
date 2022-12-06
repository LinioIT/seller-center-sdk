<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Service;

use Linio\Component\Util\Json;
use Linio\SellerCenter\Application\Security\Signature;
use Linio\SellerCenter\Factory\RequestFactory;
use Linio\SellerCenter\Factory\Xml\Document\DocumentFactory;
use Linio\SellerCenter\Formatter\LogMessageFormatter;
use Linio\SellerCenter\Model\Document\Document;
use Linio\SellerCenter\Response\HandleResponse;

class DocumentManager extends BaseManager
{
    /**
     * @param mixed[] $orderItemIds
     */
    public function getDocument(string $documentType, array $orderItemIds): Document
    {
        $action = 'GetDocument';

        $parameters = clone $this->parameters;
        $parameters->set([
            'Action' => $action,
            'DocumentType' => $documentType,
            'OrderItemIds' => Json::encode($orderItemIds),
        ]);
        $parameters->set([
            'Signature' => Signature::generate($parameters, $this->configuration->getKey())->get(),
        ]);

        $requestHeaders = $this->generateRequestHeaders();
        $requestId = $requestHeaders[self::REQUEST_ID_HEADER];

        $request = RequestFactory::make(
            'GET',
            $this->configuration->getEndpoint(),
            $requestHeaders
        );

        $response = $this->client->send($request, [
            'query' => $parameters->all(),
        ]);

        $body = (string) $response->getBody();
        $builtResponse = HandleResponse::parse($body);

        $this->logger->debug(
            LogMessageFormatter::fromAction($requestId, $action, LogMessageFormatter::TYPE_REQUEST),
            [
                'request' => [
                    'url' => (string) $request->getUri(),
                    'method' => $request->getMethod(),
                    'body' => (string) $request->getBody(),
                    'parameters' => $parameters->all(),
                ],
                'response' => [
                    'head' => $builtResponse->getHead()->asXML(),
                    'body' => $builtResponse->getBody()->asXML(),
                ],
            ]
        );

        return DocumentFactory::make($builtResponse->getBody()->Documents->Document);
    }
}

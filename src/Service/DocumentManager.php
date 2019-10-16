<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Service;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Request;
use Linio\Component\Util\Json;
use Linio\SellerCenter\Application\Configuration;
use Linio\SellerCenter\Application\Parameters;
use Linio\SellerCenter\Application\Security\Signature;
use Linio\SellerCenter\Factory\Xml\Document\DocumentFactory;
use Linio\SellerCenter\Formatter\LogMessageFormatter;
use Linio\SellerCenter\Model\Document\Document;
use Linio\SellerCenter\Response\HandleResponse;
use Psr\Log\LoggerInterface;

class DocumentManager
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var Configuration
     */
    protected $configuration;

    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var Parameters
     */
    protected $parameters;

    public function __construct(
        Configuration $configuration,
        ClientInterface $client,
        Parameters $parameters,
        LoggerInterface $logger
    ) {
        $this->configuration = $configuration;
        $this->client = $client;
        $this->parameters = $parameters;
        $this->logger = $logger;
    }

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

        $requestId = uniqid((string) mt_rand());

        $request = new Request('GET', $this->configuration->getEndpoint(), [
            'Request-ID' => $requestId,
        ]);

        $this->logger->debug(
            LogMessageFormatter::fromAction($requestId, $action, LogMessageFormatter::TYPE_REQUEST),
            [
                'url' => (string) $request->getUri(),
                'method' => $request->getMethod(),
                'body' => (string) $request->getBody(),
                'parameters' => $parameters->all(),
            ]
        );

        $response = $this->client->send($request, [
            'query' => $parameters->all(),
        ]);

        $body = (string) $response->getBody();

        $this->logger->debug(
            LogMessageFormatter::fromAction($requestId, $action, LogMessageFormatter::TYPE_RESPONSE),
            [
                'body' => $body,
            ]
        );

        $builtResponse = HandleResponse::parse($body);

        $this->logger->debug(
            LogMessageFormatter::fromAction($requestId, $action, LogMessageFormatter::TYPE_BUILT_RESPONSE),
            [
                'head' => $builtResponse->getHead()->asXML(),
                'body' => $builtResponse->getBody()->asXML(),
            ]
        );

        $documentResponse = DocumentFactory::make($builtResponse->getBody()->Documents->Document);

        $this->logger->info(
            sprintf(
                '%d::%s::APIResponse::SellerCenterSdk: the document was recovered',
                $request->getHeaderLine('Request-ID'),
                $action
            )
        );

        return $documentResponse;
    }
}

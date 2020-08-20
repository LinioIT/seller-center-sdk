<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Service;

use Linio\SellerCenter\Application\Configuration;
use Linio\SellerCenter\Application\Parameters;
use Linio\SellerCenter\Application\Security\Signature;
use Linio\SellerCenter\Formatter\LogMessageFormatter;
use Linio\SellerCenter\Response\HandleResponse;
use Linio\SellerCenter\Response\SuccessResponse;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Log\LoggerInterface;

class BaseManager
{
    protected const DATE_TIME_FORMAT = 'Y-m-d\TH:i:s';

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

    /**
     * @var RequestFactoryInterface
     */
    protected $requestFactory;

    /**
     * @var StreamFactoryInterface
     */
    protected $streamFactory;

    public function __construct(
        Configuration $configuration,
        ClientInterface $client,
        Parameters $parameters,
        LoggerInterface $logger,
        RequestFactoryInterface $requestFactory,
        StreamFactoryInterface $streamFactory
    ) {
        $this->configuration = $configuration;
        $this->client = $client;
        $this->parameters = $parameters;
        $this->logger = $logger;
        $this->requestFactory = $requestFactory;
        $this->streamFactory = $streamFactory;
    }

    public function generateRequestId(): string
    {
        return bin2hex(random_bytes(16));
    }

    public function executeAction(
        string $action,
        string $requestId,
        ?Parameters $parameters = null,
        string $httpMethod = 'GET',
        string $body = ''
    ): SuccessResponse {
        if (!$parameters) {
            $parameters = clone $this->parameters;
        }

        $parameters->set(['Action' => $action]);
        $streamBody = $this->streamFactory->createStream($body);

        $fullEndpoint = $this->buildFullEndpoint(
            $this->configuration->getEndpoint(),
            $this->buildQuery($parameters)
        );

        $request = $this->requestFactory
            ->createRequest($httpMethod, $fullEndpoint)
            ->withHeader('Request-ID', $requestId)
            ->withHeader('Content-type', 'text/xml; charset=UTF8')
            ->withBody($streamBody);

        $this->logRequest($action, $requestId, $request, $parameters);

        $response = $this->client->sendRequest($request);

        $body = (string) $response->getBody();

        $this->logRawResponse($action, $requestId, $body);

        $builtResponse = HandleResponse::parse($body);

        $this->logHandledResponse($action, $requestId, $builtResponse);

        return $builtResponse;
    }

    public function buildQuery(Parameters $parameters): array
    {
        return $parameters->all() + [
            'Signature' => Signature::generate(
                $parameters,
                $this->configuration->getKey()
            )->get(),
        ];
    }

    public function buildFullEndpoint(string $endpoint, ?array $query): string
    {
        if (empty($query)) {
            return $endpoint;
        }

        return sprintf('%s?%s', $endpoint, http_build_query($query));
    }

    private function logRequest(
        string $action,
        string $requestId,
        RequestInterface $request,
        Parameters $parameters
    ): void {
        $this->logger->debug(
            LogMessageFormatter::fromAction($requestId, $action, LogMessageFormatter::TYPE_REQUEST),
            [
                'url' => (string) $request->getUri(),
                'method' => $request->getMethod(),
                'body' => (string) $request->getBody(),
                'parameters' => $parameters->all(),
            ]
        );
    }

    private function logRawResponse(string $action, string $requestId, string $body): void
    {
        $this->logger->debug(
            LogMessageFormatter::fromAction($requestId, $action, LogMessageFormatter::TYPE_RESPONSE),
            [
                'body' => $body,
            ]
        );
    }

    private function logHandledResponse(string $action, string $requestId, SuccessResponse $handledResponse): void
    {
        $this->logger->debug(
            LogMessageFormatter::fromAction($requestId, $action, LogMessageFormatter::TYPE_BUILT_RESPONSE),
            [
                'head' => $handledResponse->getHead()->asXML(),
                'body' => $handledResponse->getBody()->asXML(),
            ]
        );
    }
}

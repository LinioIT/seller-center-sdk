<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Service;

use Linio\SellerCenter\Application\Configuration;
use Linio\SellerCenter\Application\Parameters;
use Linio\SellerCenter\Application\Security\Signature;
use Linio\SellerCenter\Contract\ClientInterface;
use Linio\SellerCenter\Factory\RequestFactory;
use Linio\SellerCenter\Formatter\LogMessageFormatter;
use Linio\SellerCenter\Response\HandleResponse;
use Linio\SellerCenter\Response\SuccessResponse;
use Psr\Http\Message\RequestInterface;
use Psr\Log\LoggerInterface;

class BaseManager
{
    protected const DATE_TIME_FORMAT = 'Y-m-d\TH:i:s';
    protected const X_SOURCE_HEADER = 'X-Source';
    protected const USER_AGENT_HEADER = 'User-Agent';
    protected const REQUEST_ID_HEADER = 'Request-ID';

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

    public function makeParametersForAction(string $actionName): Parameters
    {
        $parameters = clone $this->parameters;
        $parameters->set([
            'Action' => $actionName,
        ]);

        return $parameters;
    }

    public function generateRequestId(): string
    {
        return bin2hex(random_bytes(16));
    }

    /**
     * @param mixed[] $customHeaders
     *
     * @return mixed[]
     */
    protected function generateRequestHeaders(array $customHeaders = []): array
    {
        $headers = [
            self::REQUEST_ID_HEADER => $this->generateRequestId(),
            self::X_SOURCE_HEADER => $this->configuration->getSource(),
            self::USER_AGENT_HEADER => $this->configuration->getUserAgent(),
        ];

        if (empty($customHeaders)) {
            return $headers;
        }

        return array_merge($customHeaders, $headers);
    }

    public function executeAction(
        string $action,
        Parameters $parameters,
        string $requestId,
        string $httpMethod = 'GET',
        bool $debug = true,
        ?string $body = null
    ): SuccessResponse {
        $requestHeaders = $this->generateRequestHeaders([self::REQUEST_ID_HEADER => $requestId]);

        $request = RequestFactory::make(
            $httpMethod,
            $this->configuration->getEndpoint(),
            $requestHeaders,
            $body
        );
        $response = $this->client->send($request, [
            'query' => $this->buildQuery($parameters),
        ]);

        $body = (string) $response->getBody();
        $builtResponse = HandleResponse::parse($body);

        if ($debug) {
            $this->logRequest($action, $requestId, $request, $parameters, $builtResponse);
        }

        HandleResponse::validate($body);

        return $builtResponse;
    }

    /**
     * @return mixed[]
     */
    public function buildQuery(Parameters $parameters): array
    {
        return $parameters->all() + [
            'Signature' => Signature::generate(
                $parameters,
                $this->configuration->getKey()
            )->get(),
        ];
    }

    private function logRequest(
        string $action,
        string $requestId,
        RequestInterface $request,
        Parameters $parameters,
        SuccessResponse $handledResponse
    ): void {
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
                    'head' => $handledResponse->getHead()->asXML(),
                    'body' => $handledResponse->getBody()->asXML(),
                ],
            ]
        );
    }
}

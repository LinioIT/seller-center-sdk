<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Service;

use DateTimeImmutable;
use Linio\SellerCenter\Application\Configuration;
use Linio\SellerCenter\Application\Parameters;
use Linio\SellerCenter\Application\Security\Signature;
use Linio\SellerCenter\Contract\ClientInterface;
use Linio\SellerCenter\Factory\RequestFactory;
use Linio\SellerCenter\Formatter\LogMessageFormatter;
use Linio\SellerCenter\Response\HandleResponse;
use Linio\SellerCenter\Response\SuccessJsonResponse;
use Linio\SellerCenter\Response\SuccessResponse;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

class BaseManager
{
    protected const DATE_TIME_FORMAT = 'Y-m-d\TH:i:s';
    protected const X_SOURCE_HEADER = 'X-Source';
    protected const USER_AGENT_HEADER = 'User-Agent';
    protected const REQUEST_ID_HEADER = 'Request-ID';
    protected const CONTENT_TYPE_HEADER = 'Content-type';
    protected const CONTENT_TYPE_HEADER_VALUE = 'text/xml; charset=UTF8';
    protected const CONTENT_TYPE_HEADER_VALUE_ALT = 'application/json';

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

    public function executeAction(
        string $action,
        Parameters $parameters,
        ?string $requestId,
        string $httpMethod = 'GET',
        bool $debug = true,
        ?string $body = null
    ): SuccessResponse {
        $requestHeaders = $this->generateRequestHeaders(
            [self::REQUEST_ID_HEADER => $requestId ?? $this->generateRequestId()]
        );

        $request = RequestFactory::make(
            $httpMethod,
            $this->configuration->getEndpoint(),
            $requestHeaders,
            $body
        );

        $response = $this->generateRequest(false, $parameters, $request);

        $body = (string) $response->getBody();
        $builtResponse = HandleResponse::parse($body);

        if ($debug) {
            $this->logRequest(
                $action,
                $requestHeaders[self::REQUEST_ID_HEADER],
                $request,
                $parameters,
                [
                    'head' => $builtResponse->getHead()->asXML(),
                    'body' => $builtResponse->getBody()->asXML(),
                ]
            );
        }

        HandleResponse::validate($body);

        return $builtResponse;
    }

    /**
     * @param string[] $customHeader
     */
    public function executeJsonAction(
        string $action,
        Parameters $parameters,
        ?string $requestId,
        string $httpMethod = 'GET',
        bool $debug = true,
        ?string $body = null,
        bool $useHeaderParams = false,
        array $customHeader = [],
        string $path = ''
    ): SuccessJsonResponse {
        $requestHeaders = $this->generateRequestHeaders(
            $customHeader,
            $requestId,
            $action,
            $useHeaderParams,
            false
        );

        $request = RequestFactory::make(
            $httpMethod,
            sprintf('%s%s', $this->configuration->getEndpoint(), $path),
            $requestHeaders,
            $body
        );

        $response = $this->generateRequest($useHeaderParams, $parameters, $request);

        $body = (string) $response->getBody();
        $builtResponse = HandleResponse::parseJson($body);

        if ($debug) {
            $this->logRequest(
                $action,
                $requestHeaders[self::REQUEST_ID_HEADER],
                $request,
                $parameters,
                [
                    'message' => $builtResponse->getMessage(),
                    'data' => $builtResponse->getDataToString(),
                ]
            );
        }

        HandleResponse::validateJsonResponse($body);

        return $builtResponse;
    }

    private function generateRequest(
        bool $useHeaderParams,
        Parameters $parameters,
        RequestInterface $request
    ): ResponseInterface {
        if (!$useHeaderParams) {
            $query = $this->buildQuery($parameters);
        }

        return $this->client->send($request, [
            'query' => $query ?? $parameters->all(),
        ]);
    }

    /**
     * @return mixed[]
     */
    public function buildQuery(Parameters $parameters): array
    {
        $parameters = $parameters->all();

        return $parameters + [
            'Signature' => Signature::generate(
                $parameters,
                $this->configuration->getKey()
            )->get(),
        ];
    }

    /**
     * @param mixed[] $response
     */
    private function logRequest(
        string $action,
        string $requestId,
        RequestInterface $request,
        Parameters $parameters,
        array $response
    ): void {
        $this->logger->debug(
            LogMessageFormatter::fromAction($requestId, $action, LogMessageFormatter::TYPE_REQUEST),
            [
                'request' => [
                    'url' => (string) $request->getUri(),
                    'method' => $request->getMethod(),
                    'headers' => $request->getHeaders(),
                    'body' => (string) $request->getBody(),
                    'parameters' => $parameters->all(),
                ],
                'response' => $response,
            ]
        );
    }

    /**
     * @param string[] $customHeader
     *
     * @return mixed[]
     */
    public function generateRequestHeaders(array $customHeader = [], ?string $requestId = null, string $action = '', bool $useHeaderParams = false, bool $isXml = true): array
    {
        $header = [
            self::CONTENT_TYPE_HEADER => $isXml ? self::CONTENT_TYPE_HEADER_VALUE : self::CONTENT_TYPE_HEADER_VALUE_ALT,
            self::REQUEST_ID_HEADER => $requestId ?? $this->generateRequestId(),
            self::X_SOURCE_HEADER => $this->configuration->getSource(),
            self::USER_AGENT_HEADER => $this->configuration->getUserAgent(),
        ];

        $headerComplete = $customHeader;

        if ($useHeaderParams) {
            $headerComplete['UserID'] = $this->configuration->getUser();
            $headerComplete['Version'] = $this->configuration->getVersion();
            $headerComplete['Format'] = $isXml ? 'XML' : 'JSON';
            $headerComplete['Timestamp'] = (new DateTimeImmutable())->format(DATE_ATOM);
            $headerComplete['Action'] = $action;
            ksort($headerComplete);
            $headerComplete['Signature'] = Signature::generate(
                $headerComplete,
                $this->configuration->getKey()
            )->get();
        }

        return $headerComplete + $header;
    }
}

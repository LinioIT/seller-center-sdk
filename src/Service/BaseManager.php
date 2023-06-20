<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Service;

use Linio\Component\Util\Json;
use Linio\SellerCenter\Application\Configuration;
use Linio\SellerCenter\Application\Parameters;
use Linio\SellerCenter\Application\Security\Signature;
use Linio\SellerCenter\Contract\ClientInterface;
use Linio\SellerCenter\Contract\SuccessResponse as ContractSuccessResponse;
use Linio\SellerCenter\Factory\RequestFactory;
use Linio\SellerCenter\Formatter\LogMessageFormatter;
use Linio\SellerCenter\Response\HandleResponse;
use Linio\SellerCenter\Response\SuccessJsonResponse;
use Linio\SellerCenter\Response\SuccessResponse;
use Psr\Http\Message\RequestInterface;
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

    //original
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

        $response = $this->client->send($request, [
            'query' => $this->buildQuery($parameters),
        ]);

        $body = (string) $response->getBody();
        $builtResponse = HandleResponse::parse($body);

        if ($debug) {
            $this->logRequest(
                $action,
                $requestHeaders[self::REQUEST_ID_HEADER],
                $request,
                $parameters,
                $builtResponse->getHead()->asXML(),
                $builtResponse->getBody()->asXML()
            );
        }

        HandleResponse::validate($body);

        return $builtResponse;
    }
    //dos
    public function executeActionJson(
        string $action,
        Parameters $parameters,
        ?string $requestId,
        string $httpMethod = 'GET',
        bool $debug = true,
        ?string $body = null,
        bool $useHeaderParams = false,
        array $customHeader = [],
        string $extraPath = ''
    ): SuccessJsonResponse {

        $requestHeaders = $this->generateRequestHeaders($customHeader, $requestId, $action, $useHeaderParams, false);

        $request = RequestFactory::make(
            $httpMethod,
            sprintf('%s%s', $this->configuration->getEndpoint(), $extraPath),
            $requestHeaders,
            $body
        );

        if(!$useHeaderParams){
            $query = $this->buildQuery($parameters);
        }

        $response = $this->client->send($request, [
            'query' => $query ?? $parameters,
        ]);

        $body = (string) $response->getBody();
        $builtResponse = HandleResponse::parseJson($body);

        if ($debug) {
            $this->logRequest(
                $action,
                $requestHeaders[self::REQUEST_ID_HEADER],
                $request,
                $parameters,
                Json::encode($builtResponse->getMessage()),
                Json::encode($builtResponse->getData())
            );
        }

        HandleResponse::validateJsonResponse($body);

        return $builtResponse;
    }
    //tres

    // /**
    //  * @param string[] $customHeader
    //  */
    // public function executeAction(
    //     string $action,
    //     Parameters $parameters,
    //     ?string $requestId,
    //     string $httpMethod = 'GET',
    //     bool $debug = true,
    //     ?string $body = null,
    //     bool $useHeaderParams = false,
    //     array $customHeader = [],
    //     bool $isXml = true,
    //     string $extraPath = ''
    // ): ContractSuccessResponse {
    //     $requestHeaders = $this->generateRequestHeaders($customHeader, $requestId, $action, $useHeaderParams, $isXml);

    //     $request = RequestFactory::make(
    //         $httpMethod,
    //         sprintf('%s%s', $this->configuration->getEndpoint(), $extraPath),
    //         $requestHeaders,
    //         $body
    //     );

    //     if (!$useHeaderParams) {
    //         $query = $this->buildQuery($parameters);
    //     }

    //     $response = $this->client->send($request, [
    //         'query' => $query ?? $parameters,
    //     ]);

    //     $body = (string) $response->getBody();
    //     $builtResponse = $this->parseResponse($body, $isXml);

    //     if ($debug) {
    //         $this->logRequest(
    //             $action,
    //             $requestHeaders[self::REQUEST_ID_HEADER],
    //             $request,
    //             $parameters,
    //             $builtResponse->getBaseData(),
    //             $builtResponse->getDetailData()
    //         );
    //     }

    //     $this->validateResponse($body, $isXml);

    //     return $builtResponse;
    // }

    private function validateResponse(string $body, bool $isXml): void
    {
        if ($isXml) {
            HandleResponse::validate($body);

            return;
        }

        HandleResponse::validateJsonResponse($body);
    }

    // private function parseResponse(string $body, bool $isXml): ContractSuccessResponse
    // {
    //     if ($isXml) {
    //         return HandleResponse::parse($body);
    //     }

    //     return HandleResponse::parseJson($body);
    // }

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

    private function logRequest(
        string $action,
        string $requestId,
        RequestInterface $request,
        Parameters $parameters,
        string $head,
        string $body
    ): void {
        var_dump($head, $body);
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
                'response' => [
                    'head' => $head,
                    'body' => $body,
                ],
            ]
        );
    }

    /**
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

        $headerComplete = $header + $customHeader;

        if ($useHeaderParams) {
            $headerComplete['UserID'] = $this->configuration->getUser();
            $headerComplete['Version'] = $this->configuration->getVersion();
            $headerComplete['Format'] = $isXml ? 'XML' : 'JSON';
            $headerComplete['Timestamp'] = 'Timestamps';
            $headerComplete['Action'] = $action;

            $headerComplete['Signature'] = Signature::generate(
                $headerComplete,
                $this->configuration->getKey()
            )->get();
        }

        return $headerComplete;
    }
}

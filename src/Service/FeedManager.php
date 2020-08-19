<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Service;

use DateTimeInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Request;
use Linio\SellerCenter\Application\Configuration;
use Linio\SellerCenter\Application\Parameters;
use Linio\SellerCenter\Application\Security\Signature;
use Linio\SellerCenter\Factory\Xml\Feed\FeedFactory;
use Linio\SellerCenter\Factory\Xml\Feed\FeedsFactory;
use Linio\SellerCenter\Formatter\LogMessageFormatter;
use Linio\SellerCenter\Model\Feed\Feed;
use Linio\SellerCenter\Response\HandleResponse;
use Linio\SellerCenter\Response\SuccessResponse;
use Psr\Http\Message\RequestInterface;
use Psr\Log\LoggerInterface;

class FeedManager
{
    private const FEED_OFFSET_LIST_ACTION = 'FeedOffsetList';

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

    public function getFeedStatusById(string $id): Feed
    {
        $action = 'FeedStatus';

        $parameters = clone $this->parameters;
        $parameters->set([
            'Action' => $action,
            'FeedID' => $id,
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

        $response = $this->client->send($request, ['query' => $parameters->all()]);

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

        $feedResponse = FeedFactory::make($builtResponse->getBody()->FeedDetail);

        $this->logger->info(
            sprintf(
                '%d::%s::APIResponse::SellerCenterSdk: the feed was recovered',
                $request->getHeaderLine('Request-ID'),
                $action
            )
        );

        return $feedResponse;
    }

    /**
     * @return Feed[]
     */
    public function getFeedList(): array
    {
        $action = 'FeedList';

        $parameters = clone $this->parameters;
        $parameters->set([
            'Action' => $action,
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

        $response = $this->client->send($request, ['query' => $parameters->all()]);

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

        $list = FeedsFactory::make($builtResponse->getBody());

        $feedsResponse = array_values($list->all());

        $this->logger->info(
            sprintf(
                '%d::%s::APIResponse::SellerCenterSdk: %d feeds was recovered',
                $request->getHeaderLine('Request-ID'),
                $action,
                count($list->all())
            )
        );

        return $feedsResponse;
    }

    public function getFeedOffsetList(
        ?int $offset = null,
        ?int $pageSize = null,
        ?string $status = null,
        ?DateTimeInterface $createdAfter = null,
        ?DateTimeInterface $updatedAfter = null
    ) {
        $action = self::FEED_OFFSET_LIST_ACTION;
        $parameters = $this->makeParametersForAction($action);

        $formattedCreatedAfter = null;
        $formattedUpdatedAfter = null;

        if ($createdAfter) {
            $formattedCreatedAfter = $createdAfter->format('Y-m-d\TH:i:s');
        }

        if ($updatedAfter) {
            $formattedCreatedAfter = $updatedAfter->format('Y-m-d\TH:i:s');
        }

        $parameters->set([
            'Offset' => $offset,
            'PageSize' => $pageSize,
            'Status' => $status,
            'CreationDate' => $formattedCreatedAfter,
            'UpdatedDate' => $formattedCreatedAfter,
        ]);

        $this->updateSignature($parameters);
        $requestId = $this->generateRequestId();
        $response = $this->executeAction($action, $parameters, $requestId);
        $list = FeedsFactory::make($response->getBody());

        $this->logger->info(sprintf(
            '%d::%s::APIResponse::SellerCenterSdk: %d feeds was recovered',
            $requestId,
            $action,
            count($list->all())
        ));

        return array_values($list->all());
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

    private function makeParametersForAction(string $actionName): Parameters
    {
        $parameters = clone $this->parameters;
        $parameters->set([
            'Action' => $actionName,
        ]);

        return $parameters;
    }

    private function generateRequestId(): string
    {
        return uniqid((string) mt_rand());
    }

    private function updateSignature(Parameters $parameters): void
    {
        $parameters->set([
            'Signature' => Signature::generate(
                $parameters,
                $this->configuration->getKey()
            )->get(),
        ]);
    }

    private function executeAction(
        string $action,
        Parameters $parameters,
        string $requestId
    ): SuccessResponse {
        $request = new Request('GET', $this->configuration->getEndpoint(), [
            'Request-ID' => $requestId,
        ]);

        $this->logRequest($action, $requestId, $request, $parameters);

        $response = $this->client->send($request, ['query' => $parameters->all()]);

        $body = (string) $response->getBody();

        $this->logRawResponse($action, $requestId, $body);

        $builtResponse = HandleResponse::parse($body);

        $this->logHandledResponse($action, $requestId, $builtResponse);

        return $builtResponse;
    }
}

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
use Psr\Log\LoggerInterface;

class FeedManager
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
        $action = 'FeedOffsetList';

        $formattedCreatedAfter = null;
        $formattedUpdatedAfter = null;

        if ($createdAfter) {
            $formattedCreatedAfter = $createdAfter->format('Y-m-d\TH:i:s');
        }

        if ($updatedAfter) {
            $formattedCreatedAfter = $updatedAfter->format('Y-m-d\TH:i:s');
        }

        $parameters = clone $this->parameters;
        $parameters->set([
            'Action' => $action,
            'Offset' => $offset,
            'PageSize' => $pageSize,
            'Status' => $status,
            'CreationDate' => $formattedCreatedAfter,
            'UpdatedDate' => $formattedCreatedAfter,
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
}

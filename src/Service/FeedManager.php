<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Service;

use DateTimeInterface;
use GuzzleHttp\Psr7\Request;
use Linio\SellerCenter\Application\Security\Signature;
use Linio\SellerCenter\Factory\Xml\Feed\FeedCountFactory;
use Linio\SellerCenter\Factory\Xml\Feed\FeedFactory;
use Linio\SellerCenter\Factory\Xml\Feed\FeedsFactory;
use Linio\SellerCenter\Formatter\LogMessageFormatter;
use Linio\SellerCenter\Model\Feed\Feed;
use Linio\SellerCenter\Model\Feed\FeedCount;
use Linio\SellerCenter\Response\HandleResponse;
use SimpleXMLElement;

class FeedManager extends BaseManager
{
    private const FEED_OFFSET_LIST_ACTION = 'FeedOffsetList';

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
            $formattedCreatedAfter = $createdAfter->format(self::DATE_TIME_FORMAT);
        }

        if ($updatedAfter) {
            $formattedCreatedAfter = $updatedAfter->format(self::DATE_TIME_FORMAT);
        }

        $parameters->set([
            'Offset' => $offset,
            'PageSize' => $pageSize,
            'Status' => $status,
            'CreationDate' => $formattedCreatedAfter,
            'UpdatedDate' => $formattedCreatedAfter,
        ]);

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

    public function getFeedCount(): FeedCount
    {
        $action = 'FeedCount';
        $requestId = bin2hex(random_bytes(16));

        $response = $this->getResponse($action, $requestId);

        $this->logger->info(
            sprintf(
                '%d::%s::APIResponse::SellerCenterSdk: feed count was recovered',
                $requestId,
                $action
            )
        );

        return FeedCountFactory::make($response);
    }

    private function getResponse(string $action, string $requestId): SimpleXMLElement
    {
        $parameters = clone $this->parameters;
        $parameters->set([
            'Action' => $action,
        ]);
        $parameters->set([
            'Signature' => Signature::generate($parameters, $this->configuration->getKey())->get(),
        ]);

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

        return $builtResponse->getBody();
    }
}

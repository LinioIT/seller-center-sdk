<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Service;

use DateTimeInterface;
use Linio\SellerCenter\Application\Security\Signature;
use Linio\SellerCenter\Factory\RequestFactory;
use Linio\SellerCenter\Factory\Xml\Feed\FeedCountFactory;
use Linio\SellerCenter\Factory\Xml\Feed\FeedFactory;
use Linio\SellerCenter\Factory\Xml\Feed\FeedsFactory;
use Linio\SellerCenter\Factory\Xml\FeedResponseFactory;
use Linio\SellerCenter\Formatter\LogMessageFormatter;
use Linio\SellerCenter\Model\Feed\Feed;
use Linio\SellerCenter\Model\Feed\FeedCount;
use Linio\SellerCenter\Response\FeedResponse;
use Linio\SellerCenter\Response\HandleResponse;
use SimpleXMLElement;

class FeedManager extends BaseManager
{
    private const FEED_OFFSET_LIST_ACTION = 'FeedOffsetList';
    private const FEED_CANCEL_ACTION = 'FeedCancel';

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

        $requestHeaders = $this->generateRequestHeaders();
        $requestId = $requestHeaders[self::REQUEST_ID_HEADER];

        $request = RequestFactory::make(
            'GET',
            $this->configuration->getEndpoint(),
            $requestHeaders
        );
        $response = $this->client->send($request, ['query' => $parameters->all()]);

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

        return FeedFactory::make($builtResponse->getBody()->FeedDetail);
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

        $requestHeaders = $this->generateRequestHeaders();
        $requestId = $requestHeaders[self::REQUEST_ID_HEADER];

        $request = RequestFactory::make(
            'GET',
            $this->configuration->getEndpoint(),
            $requestHeaders
        );
        $response = $this->client->send($request, ['query' => $parameters->all()]);

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

        $list = FeedsFactory::make($builtResponse->getBody());

        return array_values($list->all());
    }

    /**
     * @return Feed[]
     */
    public function getFeedOffsetList(
        ?int $offset = null,
        ?int $pageSize = null,
        ?string $status = null,
        ?DateTimeInterface $createdAfter = null,
        ?DateTimeInterface $updatedAfter = null
    ): array {
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
        $response = $this->executeAction(
            $action,
            $parameters,
            $requestId
        );
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
        $requestId = $this->generateRequestId();

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

        $requestHeaders = $this->generateRequestHeaders([self::REQUEST_ID_HEADER => $requestId]);

        $request = RequestFactory::make(
            'GET',
            $this->configuration->getEndpoint(),
            $requestHeaders
        );

        $response = $this->client->send($request, ['query' => $parameters->all()]);

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

        return $builtResponse->getBody();
    }

    public function feedCancel(string $id): FeedResponse
    {
        $action = self::FEED_CANCEL_ACTION;
        $parameters = $this->makeParametersForAction($action);
        $parameters->set([
            'FeedID' => $id,
        ]);

        $requestId = $this->generateRequestId();

        $response = $this->executeAction(
            $action,
            $parameters,
            $requestId,
            'POST'
        );

        $feedResponse = FeedResponseFactory::make($response->getHead());

        $this->logger->info(
            sprintf(
                '%d::%s::APIResponse::SellerCenterSdk: the feed was cancel',
                $requestId,
                $action
            )
        );

        return $feedResponse;
    }
}

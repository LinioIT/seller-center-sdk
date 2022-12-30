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

    public function getFeedStatusById(
        string $id,
        bool $debug = true
    ): Feed {
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

        if ($debug) {
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
        }

        return FeedFactory::make($builtResponse->getBody()->FeedDetail);
    }

    /**
     * @return Feed[]
     */
    public function getFeedList(bool $debug = true): array
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
        if ($debug) {
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
        }

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
        ?DateTimeInterface $updatedAfter = null,
        bool $debug = true
    ): array {
        $action = self::FEED_OFFSET_LIST_ACTION;
        $parameters = $this->makeParametersForAction($action);

        $formattedCreatedAfter = null;
        $formattedUpdatedAfter = null;

        if ($createdAfter) {
            $formattedCreatedAfter = $createdAfter->format(self::DATE_TIME_FORMAT);
        }

        if ($updatedAfter) {
            $formattedUpdatedAfter = $updatedAfter->format(self::DATE_TIME_FORMAT);
        }

        $parameters->set([
            'Offset' => $offset,
            'PageSize' => $pageSize,
            'Status' => $status,
            'CreationDate' => $formattedCreatedAfter,
            'UpdatedDate' => $formattedUpdatedAfter,
        ]);

        $requestId = $this->generateRequestId();
        $response = $this->executeAction(
            $action,
            $parameters,
            $requestId,
            'GET',
            $debug
        );

        $list = FeedsFactory::make($response->getBody());

        if ($debug) {
            $this->logger->info(sprintf(
                '%s::%s::APIResponse::SellerCenterSdk: %d feeds was recovered',
                $requestId,
                $action,
                count($list->all())
            ));
        }

        return array_values($list->all());
    }

    public function getFeedCount(bool $debug = true): FeedCount
    {
        $action = 'FeedCount';
        $requestId = $this->generateRequestId();

        $response = $this->getResponse(
            $action,
            $requestId,
            $debug
        );

        return FeedCountFactory::make($response);
    }

    private function getResponse(
        string $action,
        string $requestId,
        bool $debug = true
    ): SimpleXMLElement {
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

        if ($debug) {
            $this->logger->debug(
                LogMessageFormatter::fromAction($requestId, $action, LogMessageFormatter::TYPE_REQUEST),
                [
                    'request' => [
                        'url' => (string) $request->getUri(),
                        'body' => (string) $request->getBody(),
                        'parameters' => $parameters->all(),
                    ],
                    'response' => [
                        'head' => $builtResponse->getHead()->asXML(),
                        'body' => $builtResponse->getBody()->asXML(),
                    ],
                ]
            );
        }

        return $builtResponse->getBody();
    }

    public function feedCancel(
        string $id,
        bool $debug = true
    ): FeedResponse {
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
            'POST',
            $debug
        );

        return FeedResponseFactory::make($response->getHead());
    }
}

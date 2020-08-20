<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Service;

use DateTimeInterface;
use Linio\SellerCenter\Factory\Xml\Feed\FeedFactory;
use Linio\SellerCenter\Factory\Xml\Feed\FeedsFactory;
use Linio\SellerCenter\Model\Feed\Feed;

class FeedManager extends BaseManager
{
    private const FEED_STATUS_ACTION = 'FeedStatus';
    private const FEED_LIST_ACTION = 'FeedList';
    private const FEED_OFFSET_LIST_ACTION = 'FeedOffsetList';

    public function getFeedStatusById(string $id): Feed
    {
        $action = self::FEED_STATUS_ACTION;
        $parameters = clone $this->parameters;

        $parameters->set([
            'FeedID' => $id,
        ]);

        $requestId = $this->generateRequestId();

        $builtResponse = $this->executeAction($action, $requestId, $parameters);

        $feedResponse = FeedFactory::make($builtResponse->getBody()->FeedDetail);

        $this->logger->info(
            sprintf(
                '%d::%s::APIResponse::SellerCenterSdk: the feed was recovered',
                $requestId,
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
        $action = self::FEED_LIST_ACTION;

        $requestId = uniqid((string) mt_rand());

        $builtResponse = $this->executeAction($action, $requestId);

        $list = FeedsFactory::make($builtResponse->getBody());

        $feedsResponse = array_values($list->all());

        $this->logger->info(
            sprintf(
                '%d::%s::APIResponse::SellerCenterSdk: %d feeds was recovered',
                $requestId,
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
        $parameters = clone $this->parameters;

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
        $response = $this->executeAction($action, $requestId, $parameters);
        $list = FeedsFactory::make($response->getBody());

        $this->logger->info(sprintf(
            '%d::%s::APIResponse::SellerCenterSdk: %d feeds was recovered',
            $requestId,
            $action,
            count($list->all())
        ));

        return array_values($list->all());
    }
}

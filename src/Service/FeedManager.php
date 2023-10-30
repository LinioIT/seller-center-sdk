<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Service;

use DateTimeInterface;
use Linio\SellerCenter\Factory\Xml\Feed\FeedCountFactory;
use Linio\SellerCenter\Factory\Xml\Feed\FeedFactory;
use Linio\SellerCenter\Factory\Xml\Feed\FeedsFactory;
use Linio\SellerCenter\Factory\Xml\FeedResponseFactory;
use Linio\SellerCenter\Model\Feed\Feed;
use Linio\SellerCenter\Model\Feed\FeedCount;
use Linio\SellerCenter\Response\FeedResponse;

class FeedManager extends BaseManager
{
    public function getFeedStatusById(
        string $id,
        bool $debug = true
    ): Feed {
        $action = 'FeedStatus';

        $parameters = $this->makeParametersForAction($action);
        $parameters->set(['FeedID' => $id]);

        $builtResponse = $this->executeAction(
            $action,
            $parameters,
            null,
            'GET',
            $debug
        );

        return FeedFactory::make($builtResponse->getBody()->FeedDetail);
    }

    /**
     * @return Feed[]
     */
    public function getFeedList(bool $debug = true): array
    {
        $action = 'FeedList';

        $parameters = $this->makeParametersForAction($action);

        $builtResponse = $this->executeAction(
            $action,
            $parameters,
            null,
            'GET',
            $debug
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
        ?DateTimeInterface $updatedAfter = null,
        bool $debug = true
    ): array {
        $action = 'FeedOffsetList';

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

        $parameters = $this->makeParametersForAction($action);

        $builtResponse = $this->executeAction(
            $action,
            $parameters,
            null,
            'GET',
            $debug
        );

        return FeedCountFactory::make($builtResponse->getBody());
    }

    public function feedCancel(
        string $id,
        bool $debug = true
    ): FeedResponse {
        $action = 'FeedCancel';

        $parameters = $this->makeParametersForAction($action);
        $parameters->set(['FeedID' => $id]);

        $response = $this->executeAction(
            $action,
            $parameters,
            null,
            'POST',
            $debug
        );

        return FeedResponseFactory::make($response->getHead());
    }
}

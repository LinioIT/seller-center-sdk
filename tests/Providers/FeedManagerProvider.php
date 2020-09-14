<?php

declare(strict_types=1);

namespace Linio\SellerCenter;

use stdClass;

class FeedManagerProvider
{
    public function xmlTypesProvider(): array
    {
        $feedManager = new FeedManagerTest();

        return [
            ['productUpdate' => $feedManager->getSchema('Feed/FeedProductUpdate.xml')],
            ['ProductCreate' => $feedManager->getSchema('Feed/FeedProductCreate.xml')],
        ];
    }

    public function feedProvider(): array
    {
        $result = [];
        $limit = 20;

        for ($index = 1; $index <= $limit; $index++) {
            $size = random_int(1, 10);
            $pendingFeed = $this->getPendingFeedXml();
            $result[] = [$pendingFeed['xml'], $pendingFeed['feeds'], $size];
        }

        return $result;
    }

    private function pendingFeed(): stdClass
    {
        $feedManager = new FeedManagerTest();
        $feedData = new stdClass();
        $feedData->id = $feedManager->getFaker()->uuid;
        $feedData->status = 'Queued';
        $feedData->action = 'ProductUpdate';
        $feedData->creationDate = $feedManager->getFaker()->dateTime()->format('Y-m-d H:i:s');
        $feedData->updatedDate = $feedManager->getFaker()->dateTime()->format('Y-m-d H:i:s');
        $feedData->source = 'api';
        $feedData->totalRecords = random_int(0, 100000);
        $feedData->processedRecords = 0;
        $feedData->failedRecords = 0;

        return $feedData;
    }

    private function getPendingFeedXml(int $feedSize = 1): array
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>
        <SuccessResponse>
        <Head>
        <RequestId></RequestId>
        <RequestAction>FeedList</RequestAction>
        <ResponseType>Feed</ResponseType>
        <Timestamp>2013-10-28T16:33:55+0000</Timestamp>
        </Head>
        <Body>';

        for ($index = 0; $index <= $feedSize; $index++) {
            $feed = $this->pendingFeed();

            $xml .= sprintf(
                '<Feed>
                  <Feed>%s</Feed>
                  <Status>%s</Status>
                  <Action>%s</Action>
                  <CreationDate>%s</CreationDate>
                  <UpdatedDate>%s</UpdatedDate>
                  <Source>%s</Source>
                  <TotalRecords>%s</TotalRecords>
                  <ProcessedRecords>%s</ProcessedRecords>
                  <FailedRecords>%s</FailedRecords>
                  <FailureReports></FailureReports>
                </Feed>',
                $feed->id,
                $feed->status,
                $feed->action,
                $feed->creationDate,
                $feed->updatedDate,
                $feed->source,
                $feed->totalRecords,
                $feed->processedRecords,
                $feed->failedRecords
            );

            $feeds[] = $feed;
        }

        $xml .= '</Body>
        </SuccessResponse>';

        return ['xml' => $xml, 'feeds' => $feeds];
    }
}

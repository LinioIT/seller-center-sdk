<?php

declare(strict_types=1);

namespace Linio\SellerCenter;

use stdClass;
use Linio\SellerCenter\LinioTestCase;

class FeedManagerProvider
{
    public function xmlTypesProvider(): array
    {
        return [
            [
                'productUpdate' => '<?xml version="1.0" encoding="UTF-8"?>
                    <SuccessResponse>
                         <Head>
                              <RequestId/>
                              <RequestAction>FeedStatus</RequestAction>
                              <ResponseType>FeedDetail</ResponseType>
                              <Timestamp>2018-12-18T07:55:07-0600</Timestamp>
                              <RequestParameters>
                                   <FeedID>aa19d73f-ab3a-48c1-b196-9a1f18e5280e</FeedID>
                              </RequestParameters>
                         </Head>
                         <Body>
                              <FeedDetail>
                                   <Feed>aa19d73f-ab3a-48c1-b196-9a1f18e5280e</Feed>
                                   <Status>Finished</Status>
                                   <Action>ProductUpdate</Action>
                                   <CreationDate>2018-12-17 13:46:25</CreationDate>
                                   <UpdatedDate>2018-12-17 13:50:43</UpdatedDate>
                                   <Source>api</Source>
                                   <TotalRecords>1232</TotalRecords>
                                   <ProcessedRecords>1190</ProcessedRecords>
                                   <FailedRecords>114</FailedRecords>
                                   <FeedErrors>
                                        <Error>
                                             <Code>1</Code>
                                             <Message>Negative value is not allowed</Message>
                                             <SellerSku>9786077351993</SellerSku>
                                        </Error>
                                        <Error>
                                             <Code>1</Code>
                                             <Message>Seller SKU \'9788441418011\' not found</Message>
                                             <SellerSku>9788441418011</SellerSku>
                                        </Error>
                                        <Error>
                                             <Code>1</Code>
                                             <Message>Seller SKU \'9788498455984\' not found</Message>
                                             <SellerSku>9788498455984</SellerSku>
                                        </Error>
                                   </FeedErrors>
                              </FeedDetail>
                         </Body>
                    </SuccessResponse>',
            ],
            [
                'ProductCreate' => '<?xml version="1.0" encoding="UTF-8"?>
                    <SuccessResponse>
                         <Head>
                              <RequestId/>
                              <RequestAction>FeedStatus</RequestAction>
                              <ResponseType>FeedDetail</ResponseType>
                              <Timestamp>2018-12-18T07:55:07-0600</Timestamp>
                              <RequestParameters>
                                   <FeedID>aa19d73f-ab3a-48c1-b196-9a1f18e5280e</FeedID>
                              </RequestParameters>
                         </Head>
                         <Body>
                              <FeedDetail>
                                   <Feed>aa19d73f-ab3a-48c1-b196-9a1f18e5280e</Feed>
                                   <Status>Finished</Status>
                                   <Action>ProductCreate</Action>
                                   <CreationDate>2018-12-17 13:46:25</CreationDate>
                                   <UpdatedDate>2018-12-17 13:50:43</UpdatedDate>
                                   <Source>api</Source>
                                   <TotalRecords>1232</TotalRecords>
                                   <ProcessedRecords>1190</ProcessedRecords>
                                   <FailedRecords>114</FailedRecords>
                                   <FeedErrors>
                                        <Error>
                                             <Code>0</Code>
                                             <Message>SellerSku cannot be empty</Message>
                                             <SellerSku/>
                                        </Error>
                                   </FeedErrors>
                              </FeedDetail>
                         </Body>
                    </SuccessResponse>',
            ],
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
        $linioTestCase = new LinioTestCase();
        $feedData = new stdClass();
        $feedData->id = $linioTestCase->getFaker()->uuid;
        $feedData->status = 'Queued';
        $feedData->action = 'ProductUpdate';
        $feedData->creationDate = $linioTestCase->getFaker()->dateTime()->format('Y-m-d H:i:s');
        $feedData->updatedDate = $linioTestCase->getFaker()->dateTime()->format('Y-m-d H:i:s');
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

<?php

declare(strict_types=1);

namespace Linio\SellerCenter;

use DateTimeImmutable;
use Linio\SellerCenter\Application\Configuration;
use Linio\SellerCenter\Exception\ErrorResponseException;
use Linio\SellerCenter\Exception\InvalidXmlStructureException;
use Linio\SellerCenter\Model\Feed\Feed;
use Linio\SellerCenter\Model\Feed\FeedError;
use Linio\SellerCenter\Model\Feed\FeedErrors;
use stdClass;

class FeedManagerTest extends LinioTestCase
{
    use ClientHelper;

    /**
     * @dataProvider feedProvider
     */
    public function testItReturnsFeedCollectionFromValidXml(string $xml, $expectedFeeds, $size): void
    {
        $env = $this->getParameters();
        $configuration = new Configuration($env['key'], $env['username'], $env['endpoint'], $env['version']);

        $client = $this->createClientWithResponse($xml);

        $sdkClient = new SellerCenterSdk($configuration, $client);

        $feeds = $sdkClient->feeds()->getFeedList();

        $this->assertContainsOnlyInstancesOf(Feed::class, $feeds);

        $values = array_values($feeds);
        $this->assertFeeds($values, $expectedFeeds);
    }

    /**
     * @dataProvider feedProvider
     */
    public function testItReturnsFeedCollectionFromValidXmlOnFeedOffsetList(string $xml, $expectedFeeds, $size): void
    {
        $env = $this->getParameters();
        $configuration = new Configuration($env['key'], $env['username'], $env['endpoint'], $env['version']);

        $client = $this->createClientWithResponse($xml);

        $sdkClient = new SellerCenterSdk($configuration, $client);

        $feeds = $sdkClient->feeds()->getFeedOffsetList(
            1,
            1,
            'processing',
            new DateTimeImmutable(),
            new DateTimeImmutable()
        );

        $this->assertContainsOnlyInstancesOf(Feed::class, $feeds);

        $values = array_values($feeds);
        $this->assertFeeds($values, $expectedFeeds);
    }

    public function feedProvider()
    {
        $return = [];
        $limit = 20;

        for ($i = 1; $i <= $limit; $i++) {
            $size = random_int(1, 10);
            $result = $this->getPendingFeedXml();
            $return[] = [$result['xml'], $result['feeds'], $size];
        }

        return $return;
    }

    public function getPendingFeedXml(int $feedSize = 1): array
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

        for ($i = 0; $i <= $feedSize; $i++) {
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

    public function pendingFeed(): stdClass
    {
        $feedData = new stdClass();
        $feedData->id = $this->getFaker()->uuid;
        $feedData->status = 'Queued';
        $feedData->action = 'ProductUpdate';
        $feedData->creationDate = $this->getFaker()->dateTime()->format('Y-m-d H:i:s');
        $feedData->updatedDate = $this->getFaker()->dateTime()->format('Y-m-d H:i:s');
        $feedData->source = 'api';
        $feedData->totalRecords = random_int(0, 100000);
        $feedData->processedRecords = 0;
        $feedData->failedRecords = 0;

        return $feedData;
    }

    public function testItReturnsErrorResponseException(): void
    {
        $this->expectException(ErrorResponseException::class);
        $this->expectExceptionMessage('E01: Error Message');

        $xml = '<?xml version="1.0" encoding="UTF-8"?>
        <ErrorResponse>
          <Head>
            <RequestAction>GetOrder</RequestAction>
            <ErrorType>Sender</ErrorType>
            <ErrorCode>[number]</ErrorCode>
            <ErrorMessage>E01: Error Message</ErrorMessage>
          </Head>
          <Body/>
        </ErrorResponse>';

        $env = $this->getParameters();
        $configuration = new Configuration($env['key'], $env['username'], $env['endpoint'], $env['version']);
        $client = $this->createClientWithResponse($xml, 400);

        $sdkClient = new SellerCenterSdk($configuration, $client);

        $sdkClient->feeds()->getFeedList();
    }

    /**
     * @dataProvider xmlTypesProvider
     */
    public function testItReturnsFeedInstanceFromValidXml($xml): void
    {
        $sxml = simplexml_load_string($xml);
        $env = $this->getParameters();
        $configuration = new Configuration($env['key'], $env['username'], $env['endpoint'], $env['version']);
        $client = $this->createClientWithResponse($xml);

        $sdkClient = new SellerCenterSdk($configuration, $client);
        $feed = $sdkClient->feeds()->getFeedStatusById('aa19d73f-ab3a-48c1-b196-9a1f18e5280e');

        $this->assertInstanceOf(Feed::class, $feed);
        $this->assertEquals('aa19d73f-ab3a-48c1-b196-9a1f18e5280e', $feed->getID());

        $this->assertEquals('Finished', $feed->getStatus());
        $this->assertEquals((string) $sxml->Body->FeedDetail->Action, $feed->getAction());
        $this->assertInstanceOf(DateTimeImmutable::class, $feed->getCreationDate());
        $this->assertInstanceOf(DateTimeImmutable::class, $feed->getUpdatedDate());
        $this->assertEquals('1232', $feed->getTotalRecords());
        $this->assertEquals('1190', $feed->getProcessedRecords());
        $this->assertEquals('114', $feed->getFailedRecords());

        $this->assertInstanceOf(FeedErrors::class, $feed->getErrors());
        $this->assertContainsOnlyInstancesOf(FeedError::class, $feed->getErrors()->all());
    }

    public function xmlTypesProvider()
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

    public function testItThrowsXMLStructureException(): void
    {
        $this->expectException(InvalidXmlStructureException::class);

        $xml = '<?xml version="1.0" encoding="UTF-8"?>
        <SuccessResponse>
          <Head>
            <RequestId></RequestId>
            <RequestAction>FeedList</RequestAction>
            <ResponseType>Feed</ResponseType>
            <Timestamp>2013-10-28T16:33:55+0000</Timestamp>
          </Head>
          <Body>
            <Feed>
              <Feed>829a8d2a-d370-4fa6-8613-8554f43d5fed</Feed>
              <Status>Processing</Status>
              <Action>ProductCreate</Action>
              <CreationDate>2013-10-23 15:43:26</CreationDate>
              <UpdatedDate></UpdatedDate>
              <Source>api</Source>
              <TotalRecords>9999</TotalRecords>
              <ProcessedRecords>0</ProcessedRecords>
              <FailedRecords>0</FailedRecords>
              <FailureReports></FailureReports>
            </Feed>
            <Feed>
              <Feed>829a8d2a-d370-4fa6-8613-8554f43d5fed</Feed>
              <Action>ProductCreate</Action>
              <CreationDate>2013-10-23 15:43:26</CreationDate>
              <UpdatedDate></UpdatedDate>
              <Source>api</Source>
              <TotalRecords>9999</TotalRecords>
              <ProcessedRecords>0</ProcessedRecords>
              <FailedRecords>10</FailedRecords>
              <FailureReports>
                <MimeType>text/csv</MimeType>
                <File>IkVycm9yIjsiV2FybmluZyI7IlNlbGdFN....</File>
              </FailureReports>
            </Feed>
          </Body>
        </SuccessResponse>';

        $env = $this->getParameters();
        $configuration = new Configuration($env['key'], $env['username'], $env['endpoint'], $env['version']);
        $client = $this->createClientWithResponse($xml);

        $sdkClient = new SellerCenterSdk($configuration, $client);

        $sdkClient->feeds()->getFeedList();
    }

    public function testItThrowsXMLStructureException2(): void
    {
        $this->expectException(InvalidXmlStructureException::class);

        $xml = '<?xml version="1.0" encoding="UTF-8"?>
        <SuccessResponse>
          <Head>
            <RequestId></RequestId>
            <RequestAction>FeedList</RequestAction>
            <ResponseType>Feed</ResponseType>
            <Timestamp>2013-10-28T16:33:55+0000</Timestamp>
          </Head>
          <Body>
            <Feed>
              <Status>Processing</Status>
              <Action>ProductCreate</Action>
              <CreationDate>2013-10-23 15:43:26</CreationDate>
              <UpdatedDate></UpdatedDate>
              <Source>api</Source>
              <TotalRecords>9999</TotalRecords>
              <ProcessedRecords>0</ProcessedRecords>
              <FailedRecords>0</FailedRecords>
              <FailureReports></FailureReports>
            </Feed>
            <Feed>
              <Feed>829a8d2a-d370-4fa6-8613-8554f43d5fed</Feed>
              <Status>Processing</Status>
              <Action>ProductCreate</Action>
              <CreationDate>2013-10-23 15:43:26</CreationDate>
              <UpdatedDate></UpdatedDate>
              <Source>api</Source>
              <TotalRecords>9999</TotalRecords>
              <ProcessedRecords>0</ProcessedRecords>
              <FailedRecords>10</FailedRecords>
              <FailureReports>
                <MimeType>text/csv</MimeType>
                <File>IkVycm9yIjsiV2FybmluZyI7IlNlbGdFN....</File>
              </FailureReports>
            </Feed>
          </Body>
        </SuccessResponse>';

        $env = $this->getParameters();
        $configuration = new Configuration($env['key'], $env['username'], $env['endpoint'], $env['version']);
        $client = $this->createClientWithResponse($xml);

        $sdkClient = new SellerCenterSdk($configuration, $client);

        $sdkClient->feeds()->getFeedList();
    }

    public function testItThrowsErrorResponseExceptionWithMock(): void
    {
        $this->expectException(ErrorResponseException::class);
        $this->expectExceptionMessage('E012: Invalid Feed ID');

        $xml = '<?xml version="1.0" encoding="UTF-8"?>
        <ErrorResponse>
             <Head>
                  <RequestAction>FeedStatus</RequestAction>
                  <ErrorType>Sender</ErrorType>
                  <ErrorCode>12</ErrorCode>
                  <ErrorMessage>E012: Invalid Feed ID</ErrorMessage>
             </Head>
             <Body/>
        </ErrorResponse>';

        $env = $this->getParameters();
        $configuration = new Configuration($env['key'], $env['username'], $env['endpoint'], $env['version']);
        $client = $this->createClientWithResponse($xml, 400);

        $sdkClient = new SellerCenterSdk($configuration, $client);

        $sdkClient->feeds()->getFeedStatusById('aa19d73f-ab3a-48c1-b196-9a1f18e5280e');
    }

    /**
     * @param Feed[] $feeds
     * @param stdClass[] $expectedFeeds
     */
    private function assertFeeds(array $feeds, array $expectedFeeds): void
    {
        foreach ($feeds as $key => $feed) {
            $this->assertEquals($expectedFeeds[$key]->id, $feed->getId());
            $this->assertEquals((string) $expectedFeeds[$key]->status, $feed->getStatus());
            $this->assertEquals((string) $expectedFeeds[$key]->action, $feed->getAction());
            $this->assertEquals((string) $expectedFeeds[$key]->source, $feed->getSource());
            $this->assertEquals((int) $expectedFeeds[$key]->totalRecords, $feed->getTotalRecords());
            $this->assertEquals((int) $expectedFeeds[$key]->processedRecords, $feed->getProcessedRecords());
            $this->assertEquals((int) $expectedFeeds[$key]->failedRecords, $feed->getFailedRecords());

            if (!empty($expectedFeeds[$key]->failureReport)) {
                $this->assertSame(
                    (string) $expectedFeeds[$key]->failureReport->mimeType,
                    $feed->getFailureReports()->getMimeType()
                );
                $this->assertSame(
                    (string) $expectedFeeds[$key]->failureReport->file,
                    $feed->getFailureReports()->getFile()
                );
            }
        }
    }

    public function testItReturnsFeedCount(): void
    {
        $env = $this->getParameters();
        $configuration = new Configuration($env['key'], $env['username'], $env['endpoint'], $env['version']);

        $total = 5;
        $queued = 4;
        $processing = 3;
        $finished = 2;
        $canceled = 1;

        $xmlSchema = sprintf(
            $this->getSchema('FeedCount.xml'),
            $total,
            $queued,
            $processing,
            $finished,
            $canceled
        );

        $client = $this->createClientWithResponse($xmlSchema);

        $sdkClient = new SellerCenterSdk($configuration, $client);

        $feedCount = $sdkClient->feeds()->getFeedCount();

        $this->assertEquals($total, $feedCount->getTotal());
        $this->assertEquals($queued, $feedCount->getQueued());
        $this->assertEquals($processing, $feedCount->getProcessing());
        $this->assertEquals($finished, $feedCount->getFinished());
        $this->assertEquals($canceled, $feedCount->getCanceled());
    }
}

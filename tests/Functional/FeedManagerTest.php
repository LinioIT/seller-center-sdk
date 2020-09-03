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
use Linio\SellerCenter\Response\FeedResponse;
use stdClass;

class FeedManagerTest extends LinioTestCase
{
    use ClientHelper;

    /**
     * @dataProvider Linio\SellerCenter\FeedManagerProvider::feedProvider
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
     * @dataProvider Linio\SellerCenter\FeedManagerProvider::feedProvider
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

    public function testItCancelsAFeedById(): void
    {
        $env = $this->getParameters();
        $configuration = new Configuration($env['key'], $env['username'], $env['endpoint'], $env['version']);

        $xml = $this->getSchema('Feed/FeedCancel.xml');
        $client = $this->createClientWithResponse($xml);

        $sdkClient = new SellerCenterSdk($configuration, $client);

        $feedResponse = $sdkClient->feeds()->feedCancel('c685b76e-180d-484c-b0ef-7e9aee9e3f98');

        $this->assertInstanceOf(FeedResponse::class, $feedResponse);
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
     * @dataProvider Linio\SellerCenter\FeedManagerProvider::xmlTypesProvider
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

    public function testItThrowsXMLStructureExceptionWhenDontPassStatus(): void
    {
        $this->expectException(InvalidXmlStructureException::class);

        $xml = $this->getSchema('Feed/FeedInvalidStatusPending.xml');
        $env = $this->getParameters();
        $configuration = new Configuration($env['key'], $env['username'], $env['endpoint'], $env['version']);
        $client = $this->createClientWithResponse($xml);

        $sdkClient = new SellerCenterSdk($configuration, $client);

        $sdkClient->feeds()->getFeedList();
    }

    public function testItThrowsXMLStructureExceptionWhenDontPassFeedId(): void
    {
        $this->expectException(InvalidXmlStructureException::class);

        $xml = $this->getSchema('Feed/FeedInvalidWithFeedPending.xml');

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
            $this->getSchema('Feed/FeedCount.xml'),
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

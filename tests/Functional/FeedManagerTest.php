<?php

declare(strict_types=1);

namespace Linio\SellerCenter;

use DateTimeImmutable;
use Linio\SellerCenter\Exception\ErrorResponseException;
use Linio\SellerCenter\Exception\InvalidXmlStructureException;
use Linio\SellerCenter\Model\Feed\Feed;
use Linio\SellerCenter\Model\Feed\FeedError;
use Linio\SellerCenter\Model\Feed\FeedErrors;
use Linio\SellerCenter\Response\FeedResponse;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Log\LoggerInterface;
use stdClass;

class FeedManagerTest extends LinioTestCase
{
    use ClientHelper;

    /**
     * @var ObjectProphecy
     */
    protected $logger;

    public function prepareLogTest(bool $debug): void
    {
        $this->logger = $this->prophesize(LoggerInterface::class);

        $this->logger->debug(
            Argument::type('string'),
            Argument::type('array')
        )->shouldBeCalled();

        if (!$debug) {
            $this->logger->debug(
                Argument::type('string'),
                Argument::type('array')
            )->shouldNotBeCalled();
        }
    }

    /**
     * @dataProvider Linio\SellerCenter\FeedManagerProvider::feedProvider
     */
    public function testItReturnsFeedCollectionFromValidXml(string $xml, $expectedFeeds, $size): void
    {
        $sdkClient = $this->getSdkClient($xml);

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
        $sdkClient = $this->getSdkClient($xml);

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
        $xml = $this->getSchema('Feed/FeedCancel.xml');
        $sdkClient = $this->getSdkClient($xml);

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

        $sdkClient = $this->getSdkClient($xml, null, 400);

        $sdkClient->feeds()->getFeedList();
    }

    /**
     * @dataProvider Linio\SellerCenter\FeedManagerProvider::xmlTypesProvider
     */
    public function testItReturnsFeedInstanceFromValidXml($xml): void
    {
        $sxml = simplexml_load_string($xml);
        $sdkClient = $this->getSdkClient($xml);

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
        $sdkClient = $this->getSdkClient($xml);

        $sdkClient->feeds()->getFeedList();
    }

    public function testItThrowsXMLStructureExceptionWhenDontPassFeedId(): void
    {
        $this->expectException(InvalidXmlStructureException::class);

        $xml = $this->getSchema('Feed/FeedInvalidWithFeedPending.xml');

        $sdkClient = $this->getSdkClient($xml);

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

        $sdkClient = $this->getSdkClient($xml, null, 400);

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

        $sdkClient = $this->getSdkClient($xmlSchema);

        $feedCount = $sdkClient->feeds()->getFeedCount();

        $this->assertEquals($total, $feedCount->getTotal());
        $this->assertEquals($queued, $feedCount->getQueued());
        $this->assertEquals($processing, $feedCount->getProcessing());
        $this->assertEquals($finished, $feedCount->getFinished());
        $this->assertEquals($canceled, $feedCount->getCanceled());
    }

    /**
     * @dataProvider debugParameter
     */
    public function testItLogsDependingOnDebugParamWhenGetFeedStatusSuccessResponse(bool $debug): void
    {
        $body = $this->getSchema('Feed/FeedProductCreate.xml');
        $this->prepareLogTest($debug);
        $sdkClient = $this->getSdkClient($body, $this->logger);

        $sdkClient->feeds()->getFeedStatusById(
            '1adasd-qqweqw',
            $debug
        );
    }

    /**
     * @dataProvider debugParameter
     */
    public function testItLogsDependingOnDebugParamWhenGetFeedListSuccessResponse(bool $debug): void
    {
        $body = $this->getSchema('Feed/FeedListSuccessResponse.xml');
        $this->prepareLogTest($debug);
        $sdkClient = $this->getSdkClient($body, $this->logger);

        $sdkClient->feeds()->getFeedList($debug);
    }

    /**
     * @dataProvider debugParameter
     */
    public function testItLogsDependingOnDebugParamWhenGetFeedOffsetListSuccessResponse(bool $debug): void
    {
        $body = $this->getSchema('Feed/FeedListSuccessResponse.xml');
        $this->prepareLogTest($debug);
        $sdkClient = $this->getSdkClient($body, $this->logger);

        $this->logger->info(
            Argument::type('string')
        )->shouldBeCalled();

        if (!$debug) {
            $this->logger->info(
                Argument::type('string')
            )->shouldNotBeCalled();
        }

        $sdkClient->feeds()->getFeedOffsetList(
            null,
            null,
            null,
            null,
            null,
            $debug
        );
    }

    /**
     * @dataProvider debugParameter
     */
    public function testItLogsDependingOnDebugParamWhenGetFeedCountSuccessResponse(bool $debug): void
    {
        $body = $this->getSchema('Feed/FeedCount.xml');
        $this->prepareLogTest($debug);
        $sdkClient = $this->getSdkClient($body, $this->logger);

        $sdkClient->feeds()->getFeedCount($debug);
    }

    /**
     * @dataProvider debugParameter
     */
    public function testItLogsDependingOnDebugParamWhenFeedCancelSuccessResponse(bool $debug): void
    {
        $body = $this->getSchema('Feed/FeedCancel.xml');
        $this->prepareLogTest($debug);
        $sdkClient = $this->getSdkClient($body, $this->logger);

        $sdkClient->feeds()->feedCancel(
            'asd-123',
            $debug
        );
    }

    public function debugParameter()
    {
        return [
            [false],
            [true],
        ];
    }
}

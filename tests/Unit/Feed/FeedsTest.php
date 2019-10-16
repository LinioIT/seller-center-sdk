<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Feed;

use Linio\SellerCenter\Factory\Xml\Feed\FeedsFactory;
use Linio\SellerCenter\LinioTestCase;
use Linio\SellerCenter\Model\Feed\Feed;
use Linio\SellerCenter\Model\Feed\Feeds;

class FeedsTest extends LinioTestCase
{
    public function testItReturnsEmptyFeedsArray(): void
    {
        $feeds = new Feeds();
        $this->assertEmpty($feeds->all());
    }

    public function testItReturnsFeedsArrayWithOneInstanceWithin(): void
    {
        $feeds = new Feeds();

        $feed = $this->prophesize(Feed::class);
        $feed->getId()->willReturn($this->getFaker()->uuid);

        $feeds->add($feed->reveal());

        $this->assertCount(1, $feeds->all());
        $this->assertContainsOnlyInstancesOf(Feed::class, $feeds->all());
    }

    public function testItReturnsFeedsArrayWithMultipleInstancesWithin(): void
    {
        $feeds = new Feeds();

        $searchFor = $this->getFaker()->uuid;

        $feed_1 = $this->prophesize(Feed::class);
        $feed_1->getId()->willReturn($this->getFaker()->uuid);

        $feed_2 = $this->prophesize(Feed::class);
        $feed_2->getId()->willReturn($this->getFaker()->uuid);

        $feed_3 = $this->prophesize(Feed::class);
        $feed_3->getId()->willReturn($searchFor);

        $feed_4 = $this->prophesize(Feed::class);
        $feed_4->getId()->willReturn($this->getFaker()->uuid);

        $feed_5 = $this->prophesize(Feed::class);
        $feed_5->getId()->willReturn($this->getFaker()->uuid);

        $feed_6 = $this->prophesize(Feed::class);
        $feed_6->getId()->willReturn($this->getFaker()->uuid);

        $feeds->add($feed_1->reveal());
        $feeds->add($feed_2->reveal());
        $feeds->add($feed_3->reveal());
        $feeds->add($feed_4->reveal());
        $feeds->add($feed_5->reveal());
        $feeds->add($feed_6->reveal());

        $this->assertCount(6, $feeds->all());
        $this->assertContainsOnlyInstancesOf(Feed::class, $feeds->all());

        $this->assertEquals($searchFor, $feeds->all()[$searchFor]->getId());
    }

    public function testItFoundTheFeedById(): void
    {
        $feeds = new Feeds();

        $feed_1 = $this->prophesize(Feed::class);
        $feed_1->getId()->willReturn(1);

        $feed_2 = $this->prophesize(Feed::class);
        $feed_2->getId()->willReturn(2);

        $feed_3 = $this->prophesize(Feed::class);
        $feed_3->getId()->willReturn(3);

        $feed_4 = $this->prophesize(Feed::class);
        $feed_4->getId()->willReturn(4);

        $feed_5 = $this->prophesize(Feed::class);
        $feed_5->getId()->willReturn(5);

        $feed_6 = $this->prophesize(Feed::class);
        $feed_6->getId()->willReturn(6);

        $feeds->add($feed_1->reveal());
        $feeds->add($feed_2->reveal());
        $feeds->add($feed_3->reveal());
        $feeds->add($feed_4->reveal());
        $feeds->add($feed_5->reveal());
        $feeds->add($feed_6->reveal());

        $feed = $feeds->findById(3);

        $this->assertInstanceOf(Feed::class, $feed);
        $this->assertEquals(3, $feed->getId());
    }

    public function testItCreatesACollectionFromAnXml(): void
    {
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
                          <Feed>829a8d2a-d370-4fa6-8613-8554f43d5fe1</Feed>
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

        $element = simplexml_load_string($xml);

        $feeds = FeedsFactory::make($element->Body);

        $this->assertInstanceOf(Feeds::class, $feeds);
        $this->assertCount(2, $feeds->all());
        $this->assertContainsOnlyInstancesOf(Feed::class, $feeds->all());
    }
}

<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Feed;

use Linio\Component\Util\Json;
use Linio\SellerCenter\Exception\InvalidXmlStructureException;
use Linio\SellerCenter\Factory\Xml\Feed\FeedCountFactory;
use Linio\SellerCenter\LinioTestCase;
use Linio\SellerCenter\Model\Feed\FeedCount;
use SimpleXMLElement;

class FeedCountTest extends LinioTestCase
{
    public function testItCreatesAValidFeedCountInstanceFromXml(): void
    {
        $total = 5;
        $queued = 4;
        $processing = 3;
        $finished = 2;
        $canceled = 1;

        $simpleXml = $this->mockFeedCount($total, $queued, $processing, $finished, $canceled);

        $feedCount = FeedCountFactory::make($simpleXml);

        $this->assertInstanceOf(FeedCount::class, $feedCount);

        $this->assertEquals($total, $feedCount->getTotal());
        $this->assertEquals($queued, $feedCount->getQueued());
        $this->assertEquals($processing, $feedCount->getProcessing());
        $this->assertEquals($finished, $feedCount->getFinished());
        $this->assertEquals($canceled, $feedCount->getCanceled());
    }

    public function testItReturnsAJsonRepresentationOfFeedCount(): void
    {
        $total = 5;
        $queued = 4;
        $processing = 3;
        $finished = 2;
        $canceled = 1;

        $simpleXml = $this->mockFeedCount($total, $queued, $processing, $finished, $canceled);

        $feedCount = FeedCountFactory::make($simpleXml);

        $expectedJson = sprintf(
            '{"total": %d, "queued": %d, "processing": %d, "finished": %d, "canceled": %d}',
            $total,
            $queued,
            $processing,
            $finished,
            $canceled
        );
        $this->assertJsonStringEqualsJsonString($expectedJson, Json::encode($feedCount));
    }

    public function responseStructureRequirementsDataProvider()
    {
        return [
            'FeedCount' => ['FeedCount'],
            'Total' => ['Total'],
            'Queued' => ['Queued'],
            'Processing' => ['Processing'],
            'Finished' => ['Finished'],
            'Canceled' => ['Canceled'],
        ];
    }

    /**
     * @dataProvider responseStructureRequirementsDataProvider
     */
    public function testItThrowsAnExceptionAtMalformedXml(string $property): void
    {
        $message = sprintf(
            'The xml structure is not valid for a FeedCount. The property %s should exist.',
            $property
        );

        $this->expectException(InvalidXmlStructureException::class);
        $this->expectExceptionMessage($message);

        $xml = $this->mockFeedCount();

        if ($property == 'FeedCount') {
            unset($xml->FeedCount);
        } else {
            unset($xml->FeedCount->$property);
        }

        FeedCountFactory::make($xml);
    }

    public function mockFeedCount(
        int $total = 0,
        int $queued = 0,
        int $processing = 0,
        int $finished = 0,
        int $canceled = 0
    ): SimpleXMLElement {
        $xmlSchema = sprintf(
            $this->getSchema('FeedCount.xml'),
            $total,
            $queued,
            $processing,
            $finished,
            $canceled
        );

        return simplexml_load_string($xmlSchema)->Body;
    }
}

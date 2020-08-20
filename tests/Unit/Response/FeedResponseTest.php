<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Unit\Response;

use DateTime;
use Linio\SellerCenter\Exception\EmptyArgumentException;
use Linio\SellerCenter\Exception\InvalidXmlStructureException;
use Linio\SellerCenter\Factory\Xml\FeedResponseFactory;
use Linio\SellerCenter\Response\FeedResponse;
use PHPStan\Testing\TestCase;

class FeedResponseTest extends TestCase
{
    public function testCreatesAFeedFromAnXml(): void
    {
        $successResponse = '<?xml version="1.0" encoding="UTF-8"?>
            <SuccessResponse>
                <Head>
                    <RequestId>cb106552-87f3-450b-aa8b-412246a24b34</RequestId>
                    <RequestAction>ProductCreate</RequestAction>
                    <ResponseType>Xml</ResponseType>
                    <Timestamp>2016-06-22T04:40:14+0200</Timestamp>
                </Head>
                <Body/>
            </SuccessResponse>';

        $xml = simplexml_load_string($successResponse);
        $feedResponse = FeedResponseFactory::make($xml->Head);

        $this->assertInstanceOf(FeedResponse::class, $feedResponse);
        $this->assertEquals($feedResponse->getRequestId(), (string) $xml->Head->RequestId);
        $this->assertEquals($feedResponse->getRequestAction(), (string) $xml->Head->RequestAction);
        $this->assertEquals($feedResponse->getResponseType(), (string) $xml->Head->ResponseType);
        $this->assertEquals($feedResponse->getTimestamp(), DateTime::createFromFormat("Y-m-d\TH:i:sO", (string) $xml->Head->Timestamp));
    }

    public function testCreatesAFeedFromAnXmlWithRequestParametersField(): void
    {
        $successResponse = '<?xml version="1.0" encoding="UTF-8"?>
            <SuccessResponse>
              <Head>
                <RequestId></RequestId>
                <RequestAction>FeedCancel</RequestAction>
                <ResponseType></ResponseType>
                <Timestamp>2015-07-01T11:11:11+0000</Timestamp>
                <RequestParameters>
                  <FeedID>c685b76e-180d-484c-b0ef-7e9aee9e3f98</FeedID>
                </RequestParameters>
              </Head>
              <Body/>
            </SuccessResponse>
        ';

        $xml = simplexml_load_string($successResponse);
        $feedResponse = FeedResponseFactory::make($xml->Head);

        $requestParameters = $feedResponse->getRequestParameters();
        $this->assertInstanceOf(FeedResponse::class, $feedResponse);
        $this->assertEquals((string) $xml->Head->RequestParameters->FeedID, $requestParameters['FeedID']);
    }

    public function testThrowsAExceptionWithoutARequestIdInTheXml(): void
    {
        $this->expectException(InvalidXmlStructureException::class);
        $this->expectExceptionMessage('The xml structure is not valid for a Feed. The property RequestId should exist.');

        $invalidResponse = '<?xml version="1.0" encoding="UTF-8"?>
            <SuccessResponse>
                <Head>
                    <RequestAction>ProductCreate</RequestAction>
                    <ResponseType/>
                    <Timestamp>2016-06-22T04:40:14+0200</Timestamp>
                </Head>
                <Body/>
            </SuccessResponse>';

        $xml = simplexml_load_string($invalidResponse);
        FeedResponseFactory::make($xml->Head);
    }

    public function testThrowsAExceptionWithoutARequestActionInTheXml(): void
    {
        $this->expectException(InvalidXmlStructureException::class);
        $this->expectExceptionMessage('The xml structure is not valid for a Feed. The property RequestAction should exist.');

        $invalidResponse = '<?xml version="1.0" encoding="UTF-8"?>
            <SuccessResponse>
                <Head>
                    <RequestId>cb106552-87f3-450b-aa8b-412246a24b34</RequestId>
                    <ResponseType/>
                    <Timestamp>2016-06-22T04:40:14+0200</Timestamp>
                </Head>
                <Body/>
            </SuccessResponse>';

        $xml = simplexml_load_string($invalidResponse);
        FeedResponseFactory::make($xml->Head);
    }

    public function testThrowsAExceptionWithoutAResponseTypeInTheXml(): void
    {
        $this->expectException(InvalidXmlStructureException::class);
        $this->expectExceptionMessage('The xml structure is not valid for a Feed. The property ResponseType should exist.');

        $invalidResponse = '<?xml version="1.0" encoding="UTF-8"?>
            <SuccessResponse>
                <Head>
                    <RequestId>cb106552-87f3-450b-aa8b-412246a24b34</RequestId>
                    <RequestAction>ProductCreate</RequestAction>
                    <Timestamp>2016-06-22T04:40:14+0200</Timestamp>
                </Head>
                <Body/>
            </SuccessResponse>';

        $xml = simplexml_load_string($invalidResponse);
        FeedResponseFactory::make($xml->Head);
    }

    public function testThrowsAExceptionWithoutATimestampInTheXml(): void
    {
        $this->expectException(InvalidXmlStructureException::class);
        $this->expectExceptionMessage('The xml structure is not valid for a Feed. The property Timestamp should exist.');

        $invalidResponse = '<?xml version="1.0" encoding="UTF-8"?>
            <SuccessResponse>
                <Head>
                    <RequestId>cb106552-87f3-450b-aa8b-412246a24b34</RequestId>
                    <RequestAction>ProductCreate</RequestAction>
                    <ResponseType>Xml</ResponseType>
                </Head>
                <Body/>
            </SuccessResponse>';

        $xml = simplexml_load_string($invalidResponse);
        FeedResponseFactory::make($xml->Head);
    }

    public function testCreatesAFeedFromAnXmlWithNullRequestId(): void
    {
        $invalidResponse = '<?xml version="1.0" encoding="UTF-8"?>
            <SuccessResponse>
                <Head>
                    <RequestId/>
                    <RequestAction>ProductCreate</RequestAction>
                    <ResponseType/>
                    <Timestamp>2016-06-22T04:40:14+0200</Timestamp>
                </Head>
                <Body/>
            </SuccessResponse>';

        $xml = simplexml_load_string($invalidResponse);
        $feedResponse = FeedResponseFactory::make($xml->Head);

        $this->assertInstanceOf(FeedResponse::class, $feedResponse);
        $this->assertNull($feedResponse->getRequestId());
    }

    public function testThrowsExceptionWhenRequestActionIsNull(): void
    {
        $this->expectException(EmptyArgumentException::class);

        $this->expectExceptionMessage('The parameter RequestAction should not be null.');

        $invalidResponse = '<?xml version="1.0" encoding="UTF-8"?>
            <SuccessResponse>
                <Head>
                    <RequestId>cb106552-87f3-450b-aa8b-412246a24b34</RequestId>
                    <RequestAction/>
                    <ResponseType/>
                    <Timestamp>2016-06-22T04:40:14+0200</Timestamp>
                </Head>
                <Body/>
            </SuccessResponse>';

        $xml = simplexml_load_string($invalidResponse);
        FeedResponseFactory::make($xml->Head);
    }

    public function testThrowsExceptionWhenTimestampIsNull(): void
    {
        $this->expectException(EmptyArgumentException::class);

        $this->expectExceptionMessage('The parameter Timestamp should not be null.');

        $invalidResponse = '<?xml version="1.0" encoding="UTF-8"?>
            <SuccessResponse>
                <Head>
                    <RequestId>cb106552-87f3-450b-aa8b-412246a24b34</RequestId>
                    <RequestAction>ProductCreate</RequestAction>
                    <ResponseType/>
                    <Timestamp/>
                </Head>
                <Body/>
            </SuccessResponse>';

        $xml = simplexml_load_string($invalidResponse);
        FeedResponseFactory::make($xml->Head);
    }

    public function testReturnsNullInGetTimeStampWhenThisParameterIsInvalidFromAXml(): void
    {
        $invalidResponse = '<?xml version="1.0" encoding="UTF-8"?>
            <SuccessResponse>
                <Head>
                    <RequestId>cb106552-87f3-450b-aa8b-412246a24b34</RequestId>
                    <RequestAction>ProductCreate</RequestAction>
                    <ResponseType/>
                    <Timestamp>11-2</Timestamp>
                </Head>
                <Body/>
            </SuccessResponse>';

        $xml = simplexml_load_string($invalidResponse);

        $feed = FeedResponseFactory::make($xml->Head);

        $this->assertInstanceOf(FeedResponse::class, $feed);
        $this->assertNull($feed->getTimestamp());
    }
}

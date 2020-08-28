<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Unit\Response;

use DateTime;
use Linio\SellerCenter\Exception\EmptyArgumentException;
use Linio\SellerCenter\Factory\Xml\FeedResponseFactory;
use Linio\SellerCenter\LinioTestCase;
use Linio\SellerCenter\Response\FeedResponse;

class FeedResponseTest extends LinioTestCase
{
    public function testCreatesAFeedFromAnXml(): void
    {
        $response = $this->getSchema('Product/ProductCreate.xml');

        $xml = simplexml_load_string($response);
        $feedResponse = FeedResponseFactory::make($xml->Head);

        $this->assertInstanceOf(FeedResponse::class, $feedResponse);
        $this->assertEquals($feedResponse->getRequestId(), (string) $xml->Head->RequestId);
        $this->assertEquals($feedResponse->getRequestAction(), (string) $xml->Head->RequestAction);
        $this->assertEquals($feedResponse->getResponseType(), (string) $xml->Head->ResponseType);
        $this->assertEquals($feedResponse->getTimestamp(), DateTime::createFromFormat("Y-m-d\TH:i:sO", (string) $xml->Head->Timestamp));
    }

    public function testCreatesAFeedFromAnXmlWithRequestParametersField(): void
    {
        $response = $this->getSchema('Feed/FeedCancel.xml');

        $xml = simplexml_load_string($response);
        $feedResponse = FeedResponseFactory::make($xml->Head);

        $requestParameters = $feedResponse->getRequestParameters();
        $this->assertInstanceOf(FeedResponse::class, $feedResponse);
        $this->assertEquals((string) $xml->Head->RequestParameters->FeedID, $requestParameters['FeedID']);
    }

    public function testCreatesAFeedFromAnXmlWithNullRequestId(): void
    {
        $response = $this->getSchema('Product/ProductCreate.xml');

        $xml = simplexml_load_string($response);
        $xml->Head->RequestId = null;

        $feedResponse = FeedResponseFactory::make($xml->Head);

        $this->assertInstanceOf(FeedResponse::class, $feedResponse);
        $this->assertNull($feedResponse->getRequestId());
    }

    public function testThrowsExceptionWhenRequestActionIsNull(): void
    {
        $this->expectException(EmptyArgumentException::class);
        $this->expectExceptionMessage('The parameter RequestAction should not be null.');

        $response = $this->getSchema('Product/ProductCreate.xml');

        $xml = simplexml_load_string($response);
        $xml->Head->RequestAction = null;

        FeedResponseFactory::make($xml->Head);
    }

    public function testThrowsExceptionWhenTimestampIsNull(): void
    {
        $this->expectException(EmptyArgumentException::class);
        $this->expectExceptionMessage('The parameter Timestamp should not be null.');

        $response = $this->getSchema('Product/ProductCreate.xml');

        $xml = simplexml_load_string($response);
        $xml->Head->Timestamp = null;

        FeedResponseFactory::make($xml->Head);
    }

    public function testReturnsNullInGetTimeStampWhenThisParameterIsInvalidFromAXml(): void
    {
        $response = $this->getSchema('Product/ProductCreate.xml');

        $xml = simplexml_load_string($response);
        $xml->Head->Timestamp = '11-2';

        $feedResponse = FeedResponseFactory::make($xml->Head);

        $this->assertInstanceOf(FeedResponse::class, $feedResponse);
        $this->assertNull($feedResponse->getTimestamp());
    }
}

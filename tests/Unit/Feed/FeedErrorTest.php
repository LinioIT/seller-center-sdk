<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Feed;

use Linio\Component\Util\Json;
use Linio\SellerCenter\Exception\EmptyArgumentException;
use Linio\SellerCenter\Exception\InvalidXmlStructureException;
use Linio\SellerCenter\Factory\Xml\Feed\FeedErrorFactory;
use Linio\SellerCenter\LinioTestCase;
use Linio\SellerCenter\Model\Feed\FeedError;
use SimpleXMLElement;

class FeedErrorTest extends LinioTestCase
{
    public function testItCreatesAValidFeedErrorInstanceFromXml(): void
    {
        $code = 0;
        $message = 'Negative value is not allowed';
        $sellerSku = '9786077351993';

        $xml = '<Error>
                     <Code>' . $code . '</Code>
                     <Message>' . $message . '</Message>
                     <SellerSku>' . $sellerSku . '</SellerSku>
                </Error>';

        $element = new SimpleXMLElement($xml);
        $feedError = FeedErrorFactory::make($element);

        $this->assertInstanceOf(FeedError::class, $feedError);

        $this->assertEquals($code, $feedError->getCode());
        $this->assertEquals($message, $feedError->getMessage());
        $this->assertEquals($sellerSku, $feedError->getSellerSku());
    }

    public function testItThrowsAnExceptionIfCodeIsMissingForFeedError(): void
    {
        $this->expectException(InvalidXmlStructureException::class);
        $this->expectExceptionMessage('The xml structure is not valid for a FeedError. The property Code should exist.');

        $xml = '<Error>
                     <Message>Negative value is not allowed</Message>
                     <SellerSku>9786077351993</SellerSku>
                </Error>';

        $element = new SimpleXMLElement($xml);
        FeedErrorFactory::make($element);
    }

    public function testItThrowsAnExceptionIfMessageIsMissingForFeedError(): void
    {
        $this->expectException(InvalidXmlStructureException::class);
        $this->expectExceptionMessage('The xml structure is not valid for a FeedError. The property Message should exist.');

        $xml = '<Error>
                     <Code>0</Code>
                     <SellerSku>9786077351993</SellerSku>
                </Error>';

        $element = new SimpleXMLElement($xml);
        FeedErrorFactory::make($element);
    }

    public function testItThrowsAnExceptionIfSellerSkuIsMissingForFeedError(): void
    {
        $this->expectException(InvalidXmlStructureException::class);
        $this->expectExceptionMessage('The xml structure is not valid for a FeedError. The property SellerSku should exist.');

        $xml = '<Error>
                     <Code>0</Code>
                     <Message>Negative value is not allowed</Message>
                </Error>';

        $element = new SimpleXMLElement($xml);
        FeedErrorFactory::make($element);
    }

    public function testItThrowsAnExceptionIfMessageIsNullForFeedError(): void
    {
        $this->expectException(EmptyArgumentException::class);
        $this->expectExceptionMessage('The parameter Message should not be null.');

        new FeedError(0, 'SellerSku', '');
    }

    public function testItReturnsAJsonRepresentation(): void
    {
        $code = 0;
        $message = 'Negative value is not allowed';
        $sellerSku = '9786077351993';

        $xml = '<Error>
                     <Code>' . $code . '</Code>
                     <Message>' . $message . '</Message>
                     <SellerSku>' . $sellerSku . '</SellerSku>
                </Error>';

        $simpleXml = simplexml_load_string($xml);

        $feedError = FeedErrorFactory::make($simpleXml);

        $expectedJson = sprintf('{"code": %d, "sku": "%s", "message": "%s"}', $code, $sellerSku, $message);
        $this->assertJsonStringEqualsJsonString($expectedJson, Json::encode($feedError));
    }
}

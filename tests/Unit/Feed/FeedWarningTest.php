<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Feed;

use Linio\Component\Util\Json;
use Linio\SellerCenter\Exception\EmptyArgumentException;
use Linio\SellerCenter\Exception\InvalidXmlStructureException;
use Linio\SellerCenter\Factory\Xml\Feed\FeedWarningFactory;
use Linio\SellerCenter\LinioTestCase;
use Linio\SellerCenter\Model\Feed\FeedWarning;
use SimpleXMLElement;

class FeedWarningTest extends LinioTestCase
{
    public function testItCreatesAValidFeedWarningInstanceFromXml(): void
    {
        $message = 'Message';
        $sellerSku = 'SellerSku';

        $xml = '<Warning>
                    <Message>' . $message . '</Message>
                    <SellerSku>' . $sellerSku . '</SellerSku>
                </Warning>';
        $element = new SimpleXMLElement($xml);
        $feedWarning = FeedWarningFactory::make($element);

        $this->assertInstanceOf(FeedWarning::class, $feedWarning);
        $this->assertEquals($message, $feedWarning->getMessage());
        $this->assertEquals($sellerSku, $feedWarning->getSellerSku());
    }

    public function testItCreatesFeedWarningInstanceUsingItsConstructor(): void
    {
        $sku = 'seller-sku-string';
        $message = 'warning-message';

        $feed = new FeedWarning($sku, $message);

        $this->assertEquals($sku, $feed->getSellerSku());
        $this->assertEquals($message, $feed->getMessage());
    }

    public function testItThrowsAnExceptionIfSellerSkuIsMissingForFeedWarning(): void
    {
        $this->expectException(InvalidXmlStructureException::class);
        $this->expectExceptionMessage('The xml structure is not valid for a FeedWarning. The property SellerSku should exist.');

        $xml = '<Warning>
                    <Message>Negative value is not allowed</Message>
                </Warning>';

        $element = new SimpleXMLElement($xml);
        FeedWarningFactory::make($element);
    }

    public function testItThrowsAnExceptionIfMessageIsMissingForFeedWarning(): void
    {
        $this->expectException(InvalidXmlStructureException::class);
        $this->expectExceptionMessage('The xml structure is not valid for a FeedWarning. The property Message should exist.');

        $xml = '<Warning>
                    <SellerSku>9786077351993</SellerSku>
                </Warning>';

        $element = new SimpleXMLElement($xml);
        FeedWarningFactory::make($element);
    }

    public function testItThrowsAnExceptionIfMessageIsNullForFeedWarning(): void
    {
        $this->expectException(EmptyArgumentException::class);
        $this->expectExceptionMessage('The parameter SellerSku should not be null.');

        new FeedWarning('', 'Message');
    }

    public function testItThrowsAnExceptionIfSellerSkuIsNullForFeedWarning(): void
    {
        $this->expectException(EmptyArgumentException::class);
        $this->expectExceptionMessage('The parameter Message should not be null.');

        new FeedWarning('SellerSku', '');
    }

    public function testItReturnsAJsonRepresentation(): void
    {
        $message = 'Message';
        $sellerSku = 'SellerSku';

        $xml = '<Warning>
                    <Message>' . $message . '</Message>
                    <SellerSku>' . $sellerSku . '</SellerSku>
                </Warning>';

        $simpleXml = simplexml_load_string($xml);

        $feedWarnnig = FeedWarningFactory::make($simpleXml);

        $expectedJson = sprintf('{"sku": "%s", "message": "%s"}', $sellerSku, $message);
        $this->assertJsonStringEqualsJsonString($expectedJson, Json::encode($feedWarnnig));
    }
}

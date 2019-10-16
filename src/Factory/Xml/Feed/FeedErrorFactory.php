<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Xml\Feed;

use Linio\SellerCenter\Exception\InvalidXmlStructureException;
use Linio\SellerCenter\Model\Feed\FeedError;
use SimpleXMLElement;

class FeedErrorFactory
{
    public static function make(SimpleXMLElement $xml): FeedError
    {
        if (!property_exists($xml, 'Code')) {
            throw new InvalidXmlStructureException('FeedError', 'Code');
        }

        if (!property_exists($xml, 'SellerSku')) {
            throw new InvalidXmlStructureException('FeedError', 'SellerSku');
        }

        if (!property_exists($xml, 'Message')) {
            throw new InvalidXmlStructureException('FeedError', 'Message');
        }

        return new FeedError((int) $xml->Code, (string) $xml->SellerSku, (string) $xml->Message);
    }
}

<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Xml\Feed;

use Linio\SellerCenter\Exception\InvalidXmlStructureException;
use Linio\SellerCenter\Model\Feed\FeedWarning;
use SimpleXMLElement;

class FeedWarningFactory
{
    public static function make(SimpleXMLElement $xml): FeedWarning
    {
        if (!property_exists($xml, 'Message')) {
            throw new InvalidXmlStructureException('FeedWarning', 'Message');
        }

        if (!property_exists($xml, 'SellerSku')) {
            throw new InvalidXmlStructureException('FeedWarning', 'SellerSku');
        }

        return new FeedWarning((string) $xml->SellerSku, (string) $xml->Message);
    }
}

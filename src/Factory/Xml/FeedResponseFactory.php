<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Xml;

use Linio\SellerCenter\Exception\InvalidXmlStructureException;
use Linio\SellerCenter\Response\FeedResponse;
use SimpleXMLElement;

class FeedResponseFactory
{
    public static function make(SimpleXMLElement $xml): FeedResponse
    {
        if (!property_exists($xml, 'RequestId')) {
            throw new InvalidXmlStructureException('Feed', 'RequestId');
        }

        if (!property_exists($xml, 'RequestAction')) {
            throw new InvalidXmlStructureException('Feed', 'RequestAction');
        }

        if (!property_exists($xml, 'ResponseType')) {
            throw new InvalidXmlStructureException('Feed', 'ResponseType');
        }

        if (!property_exists($xml, 'Timestamp')) {
            throw new InvalidXmlStructureException('Feed', 'Timestamp');
        }

        return new FeedResponse(
            (string) $xml->RequestId,
            (string) $xml->RequestAction,
            (string) $xml->ResponseType,
            (string) $xml->Timestamp
        );
    }
}

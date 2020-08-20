<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Xml\Feed;

use Linio\SellerCenter\Exception\InvalidXmlStructureException;
use Linio\SellerCenter\Model\Feed\FeedCount;
use SimpleXMLElement;

class FeedCountFactory
{
    public static function make(SimpleXMLElement $xml): FeedCount
    {
        self::validateStructure($xml, 'FeedCount');
        self::validateStructure($xml->FeedCount, 'Total');
        self::validateStructure($xml->FeedCount, 'Queued');
        self::validateStructure($xml->FeedCount, 'Processing');
        self::validateStructure($xml->FeedCount, 'Finished');
        self::validateStructure($xml->FeedCount, 'Canceled');

        return new FeedCount(
            (int) $xml->FeedCount->Total,
            (int) $xml->FeedCount->Queued,
            (int) $xml->FeedCount->Processing,
            (int) $xml->FeedCount->Finished,
            (int) $xml->FeedCount->Canceled
        );
    }

    private static function validateStructure(SimpleXMLElement $xml, string $property): void
    {
        if (!property_exists($xml, $property)) {
            throw new InvalidXmlStructureException('FeedCount', $property);
        }
    }
}

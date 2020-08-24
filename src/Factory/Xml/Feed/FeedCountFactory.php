<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Xml\Feed;

use Linio\SellerCenter\Exception\InvalidXmlStructureException;
use Linio\SellerCenter\Model\Feed\FeedCount;
use SimpleXMLElement;

class FeedCountFactory
{
    public const FEED_COUNT = 'FeedCount';
    public const PROPERTIES = [
        'Total',
        'Queued',
        'Processing',
        'Finished',
        'Canceled',
    ];

    public static function make(SimpleXMLElement $xml): FeedCount
    {
        self::validateStructure($xml, self::FEED_COUNT);

        foreach (self::PROPERTIES as $property) {
            self::validateStructure($xml->FeedCount, $property);
        }

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
            throw new InvalidXmlStructureException(self::FEED_COUNT, $property);
        }
    }
}

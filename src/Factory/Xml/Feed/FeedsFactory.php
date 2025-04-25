<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Xml\Feed;

use Linio\SellerCenter\Model\Feed\Feeds;

class FeedsFactory
{
    public static function make(\SimpleXMLElement $xml): Feeds
    {
        $feeds = new Feeds();

        foreach ($xml->Feed as $feed) {
            $feeds->add(FeedFactory::make($feed));
        }

        return $feeds;
    }
}

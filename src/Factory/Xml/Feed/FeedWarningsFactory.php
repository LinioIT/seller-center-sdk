<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Xml\Feed;

use Linio\SellerCenter\Model\Feed\FeedWarnings;
use SimpleXMLElement;

class FeedWarningsFactory
{
    public static function make(SimpleXMLElement $xml): FeedWarnings
    {
        $feedWarnings = new FeedWarnings();

        foreach ($xml->Warning as $item) {
            $warning = FeedWarningFactory::make($item);
            $feedWarnings->add($warning);
        }

        return $feedWarnings;
    }
}

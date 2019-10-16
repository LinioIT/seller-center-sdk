<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Xml\Feed;

use Linio\SellerCenter\Model\Feed\FeedErrors;
use SimpleXMLElement;

class FeedErrorsFactory
{
    public static function make(SimpleXMLElement $xml): FeedErrors
    {
        $feedErrors = new FeedErrors();

        foreach ($xml->Error as $item) {
            $error = FeedErrorFactory::make($item);
            $feedErrors->add($error);
        }

        return $feedErrors;
    }
}

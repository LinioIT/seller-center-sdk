<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Xml\Order;

use Linio\SellerCenter\Model\Order\FailureReasons;
use SimpleXMLElement;

class FailureReasonsFactory
{
    public static function make(SimpleXMLElement $xml): FailureReasons
    {
        $reasons = new FailureReasons();

        foreach ($xml->Reasons->Reason as $reason) {
            $reasons->add(FailureReasonFactory::make($reason));
        }

        return $reasons;
    }
}

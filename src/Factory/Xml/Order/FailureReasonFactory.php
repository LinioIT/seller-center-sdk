<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Xml\Order;

use Linio\SellerCenter\Exception\InvalidXmlStructureException;
use Linio\SellerCenter\Model\Order\FailureReason;
use SimpleXMLElement;

class FailureReasonFactory
{
    public static function make(SimpleXMLElement $element): FailureReason
    {
        if (!property_exists($element, 'Type')) {
            throw new InvalidXmlStructureException('FailureReason', 'Type');
        }

        if (!property_exists($element, 'Name')) {
            throw new InvalidXmlStructureException('FailureReason', 'Name');
        }

        return new FailureReason((string) $element->Type, (string) $element->Name);
    }
}

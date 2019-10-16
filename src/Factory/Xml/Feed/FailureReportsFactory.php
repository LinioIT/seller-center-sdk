<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Xml\Feed;

use Linio\SellerCenter\Exception\InvalidXmlStructureException;
use Linio\SellerCenter\Model\Feed\FailureReports;
use SimpleXMLElement;

class FailureReportsFactory
{
    public static function make(SimpleXMLElement $element): FailureReports
    {
        if (!property_exists($element, 'MimeType')) {
            throw new InvalidXmlStructureException('FailureReports', 'MimeType');
        }

        if (!property_exists($element, 'File')) {
            throw new InvalidXmlStructureException('FailureReports', 'File');
        }

        return new FailureReports((string) $element->MimeType, (string) $element->File);
    }
}

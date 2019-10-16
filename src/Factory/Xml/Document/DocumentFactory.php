<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Xml\Document;

use Linio\SellerCenter\Exception\InvalidXmlStructureException;
use Linio\SellerCenter\Model\Document\Document;
use SimpleXMLElement;

class DocumentFactory
{
    public static function make(SimpleXMLElement $element): Document
    {
        if (!property_exists($element, 'DocumentType')) {
            throw new InvalidXmlStructureException('Document', 'DocumentType');
        }

        if (!property_exists($element, 'MimeType')) {
            throw new InvalidXmlStructureException('Document', 'MimeType');
        }

        if (!property_exists($element, 'File')) {
            throw new InvalidXmlStructureException('Document', 'File');
        }

        return new Document((string) $element->DocumentType, (string) $element->MimeType, (string) $element->File);
    }
}

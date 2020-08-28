<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Xml;

use Linio\SellerCenter\Response\FeedResponse;
use Linio\SellerCenter\Validator\XmlStructureValidator;
use SimpleXMLElement;

class FeedResponseFactory
{
    private const XML_MODEL = 'Feed';
    private const REQUIRED_FIELDS = [
        'RequestId',
        'RequestAction',
        'ResponseType',
        'Timestamp',
    ];

    public static function make(SimpleXMLElement $xml): FeedResponse
    {
        XmlStructureValidator::validateStructure($xml, self::XML_MODEL, self::REQUIRED_FIELDS);

        $requestParameters = [];
        if (property_exists($xml, 'RequestParameters')) {
            foreach ($xml->RequestParameters->children() as $item) {
                $requestParameters[$item->getName()] = (string) $item;
            }
        }

        $requestId = !empty($xml->RequestId) ?
            (string) $xml->RequestId : null;

        return new FeedResponse(
            $requestId,
            (string) $xml->RequestAction,
            (string) $xml->ResponseType,
            (string) $xml->Timestamp,
            $requestParameters
        );
    }
}

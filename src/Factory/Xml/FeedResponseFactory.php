<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Xml;

use Linio\SellerCenter\Response\FeedResponse;
use Linio\SellerCenter\Response\SuccessResponse;
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

    private const REQUIRED_BODY_FIELDS = [
        'Stocks',
    ];

    private const REQUIRED_STOCK_FIELDS = [
        'feed',
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

    public static function makeForStock(SuccessResponse $xml): FeedResponse
    {
        $header = $xml->getHead();
        $body = $xml->getBody();
        XmlStructureValidator::validateStructure($header, self::XML_MODEL, self::REQUIRED_FIELDS);
        XmlStructureValidator::validateStructure($body, self::XML_MODEL, self::REQUIRED_BODY_FIELDS);
        foreach ($body->Stocks as $stock) {
            XmlStructureValidator::validateStructure($stock, self::XML_MODEL, self::REQUIRED_STOCK_FIELDS);
        }

        $requestParameters = [];
        if (property_exists($xml, 'RequestParameters')) {
            foreach ($header->RequestParameters->children() as $item) {
                $requestParameters[$item->getName()] = (string) $item;
            }
        }

        $requestId = !empty($body->Stocks->feed) ?
            (string) $body->Stocks->feed : null;

        return new FeedResponse(
            $requestId,
            (string) $header->RequestAction,
            (string) $header->ResponseType,
            (string) $header->Timestamp,
            $requestParameters
        );
    }
}

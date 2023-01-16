<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Response;

use Exception;
use Linio\SellerCenter\Application\ResponseStatus;
use Linio\SellerCenter\Exception\EmptyXmlException;
use Linio\SellerCenter\Exception\ErrorResponseException;
use Linio\SellerCenter\Exception\InvalidXmlException;
use SimpleXMLElement;

class HandleResponse
{
    public static function parse(string $data): SuccessResponse
    {
        return SuccessResponse::fromXml(self::getXml($data));
    }

    public static function validate(string $data): void
    {
        $xml = self::getXml($data);

        if ($xml->getName() == ResponseStatus::ERROR) {
            throw new ErrorResponseException($xml);
        }
    }

    public static function getXml(string $data): SimpleXMLElement
    {
        try {
            $xml = simplexml_load_string($data);
        } catch (Exception $e) {
            throw new InvalidXmlException();
        }

        if (empty($xml)) {
            throw new EmptyXmlException();
        }

        return $xml;
    }
}

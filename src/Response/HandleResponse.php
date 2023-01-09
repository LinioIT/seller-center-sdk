<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Response;

use Exception;
use SimpleXMLElement;
use Linio\SellerCenter\Application\ResponseStatus;
use Linio\SellerCenter\Exception\EmptyXmlException;
use Linio\SellerCenter\Exception\InvalidXmlException;
use Linio\SellerCenter\Exception\ErrorResponseException;

class HandleResponse
{
    public static function parse(string $data): SuccessResponse
    {
        return SuccessResponse::fromXml(HandleResponse::getXml($data));
    }

    public static function validate(string $data): void
    {
        $xml = HandleResponse::getXml($data);

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

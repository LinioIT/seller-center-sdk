<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Response;

use Exception;
use Linio\Component\Util\Json as JsonFormatter;
use Linio\SellerCenter\Application\ResponseStatus;
use Linio\SellerCenter\Exception\EmptyJsonException;
use Linio\SellerCenter\Exception\EmptyXmlException;
use Linio\SellerCenter\Exception\ErrorJsonResponseException;
use Linio\SellerCenter\Exception\ErrorResponseException;
use Linio\SellerCenter\Exception\InvalidJsonException;
use Linio\SellerCenter\Exception\InvalidXmlException;
use SimpleXMLElement;

class HandleResponse
{
    public static function parse(string $data): SuccessResponse
    {
        return SuccessResponse::fromXml(self::getXml($data));
    }

    public static function parseJson(string $data): SuccessJsonResponse
    {
        return SuccessJsonResponse::fromJson(self::getJson($data));
    }

    /**
     * @throws ErrorResponseException
     */
    public static function validate(string $data): void
    {
        $xml = self::getXml($data);

        if ($xml->getName() == ResponseStatus::ERROR) {
            throw new ErrorResponseException($xml);
        }
    }

    /**
     * @throws ErrorJsonResponseException
     */
    public static function validateJsonResponse(string $data): void
    {
        $json = JsonFormatter::decode($data);

        if (isset($json['ErrorResponse']) || isset($json['errors'])) {
            throw new ErrorJsonResponseException($json['ErrorResponse'] ?? $json['errors']);
        }
    }

    /**
     * @throws InvalidXmlException
     * @throws EmptyXmlException
     */
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

    /**
     * @throws InvalidJsonException
     * @throws EmptyJsonException
     *
     * @return mixed[]
     */
    public static function getJson(string $data): array
    {
        try {
            $json = JsonFormatter::decode($data);
        } catch (Exception $e) {
            throw new InvalidJsonException();
        }

        if (empty($json)) {
            throw new EmptyJsonException();
        }

        return $json;
    }
}

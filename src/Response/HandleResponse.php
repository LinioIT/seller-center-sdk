<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Response;

use Exception;
use Linio\SellerCenter\Application\ResponseStatus;
use Linio\SellerCenter\Exception\EmptyXmlException;
use Linio\SellerCenter\Exception\ErrorResponseException;
use Linio\SellerCenter\Exception\InvalidXmlException;

class HandleResponse
{
    public static function parse(string $data): SuccessResponse
    {
        try {
            $xml = simplexml_load_string($data);
        } catch (Exception $e) {
            throw new InvalidXmlException();
        }

        if (empty($xml)) {
            throw new EmptyXmlException();
        }

        if ($xml->getName() == ResponseStatus::ERROR) {
            throw new ErrorResponseException($xml);
        }

        return SuccessResponse::fromXml($xml);
    }
}

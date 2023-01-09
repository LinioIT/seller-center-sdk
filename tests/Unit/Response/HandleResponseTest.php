<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Response;

use Linio\SellerCenter\Exception\EmptyXmlException;
use Linio\SellerCenter\Exception\ErrorResponseException;
use Linio\SellerCenter\Exception\InvalidXmlException;
use Linio\SellerCenter\LinioTestCase;

class HandleResponseTest extends LinioTestCase
{
    public function testItThrowAnExceptionWithAnInvalidXml(): void
    {
        $this->expectException(InvalidXmlException::class);

        $response = 'invalid-xml';

        HandleResponse::parse($response);
        HandleResponse::validate($response);
    }

    public function testItThrowAnExceptionWithAnEmptyXml(): void
    {
        $this->expectException(EmptyXmlException::class);

        $response = '<xml></xml>';

        HandleResponse::parse($response);
        HandleResponse::validate($response);
    }

    public function testItThrowAnExceptionWithAnErrorResponse(): void
    {
        $this->expectException(ErrorResponseException::class);

        $xml = $this->getSchema('Response/ErrorResponse.xml');

        HandleResponse::parse($xml);
        HandleResponse::validate($xml);
    }
}

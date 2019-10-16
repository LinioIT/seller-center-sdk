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

        HandleResponse::parse('invalid-xml');
    }

    public function testItThrowAnExceptionWithAnEmptyXml(): void
    {
        $this->expectException(EmptyXmlException::class);

        HandleResponse::parse('<xml></xml>');
    }

    public function testItThrowAnExceptionWithAnErrorResponse(): void
    {
        $this->expectException(ErrorResponseException::class);

        $xml = '<?xml version="1.0" encoding="UTF-8"?>
        <ErrorResponse>
            <Head>
                <RequestAction>GetOrder</RequestAction>
                <ErrorType>Sender</ErrorType>
                <ErrorCode>125</ErrorCode>
                <ErrorMessage>E0125: Test Error</ErrorMessage>
            </Head>
            <Body/>
        </ErrorResponse>';

        HandleResponse::parse($xml);
    }
}

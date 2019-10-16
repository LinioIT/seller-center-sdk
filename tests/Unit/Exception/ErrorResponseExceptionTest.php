<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Unit\Exception;

use Linio\SellerCenter\Exception\ErrorResponseException;
use Linio\SellerCenter\LinioTestCase;

class ErrorResponseExceptionTest extends LinioTestCase
{
    public function testItLoadsAnExceptionFromAnXml(): void
    {
        $string = '<?xml version="1.0" encoding="UTF-8"?>
                                    <ErrorResponse>
                                        <Head>
                                            <RequestAction>GetOrder</RequestAction>
                                            <ErrorType>Sender</ErrorType>
                                            <ErrorCode>105</ErrorCode>
                                            <ErrorMessage>E01: Error Message</ErrorMessage>
                                        </Head>
                                        <Body/>
                                    </ErrorResponse>';

        $xml = simplexml_load_string($string);

        $error = new ErrorResponseException($xml);

        $this->assertEquals('Sender', $error->getType());
        $this->assertEquals('105', $error->getCode());
        $this->assertEquals('E01: Error Message', $error->getMessage());
        $this->assertEquals('GetOrder', $error->getAction());
    }
}

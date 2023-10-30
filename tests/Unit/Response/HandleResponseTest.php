<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Response;

use Linio\SellerCenter\Exception\EmptyJsonException;
use Linio\SellerCenter\Exception\EmptyXmlException;
use Linio\SellerCenter\Exception\ErrorJsonResponseException;
use Linio\SellerCenter\Exception\ErrorResponseException;
use Linio\SellerCenter\Exception\InvalidJsonException;
use Linio\SellerCenter\Exception\InvalidXmlException;
use Linio\SellerCenter\LinioTestCase;

class HandleResponseTest extends LinioTestCase
{
    public function testItValidateSuccessXml(): void
    {
        $response = '<xml><Test>test</Test></xml>';
        $expected = '';

        $result = HandleResponse::parse($response);
        HandleResponse::validate($response);

        $this->assertInstanceOf(SuccessResponse::class, $result);
    }

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

    public function testItParseJson(): void
    {
        $json = $this->getSchema('Response/InvoiceDocumentSuccessResponse.json');

        $result = HandleResponse::parseJson($json);
        $expectedMessage = 'Invoice Uploaded Successfully';
        $expectedData = [
            'invoiceNumber' => 0,
            'invoiceDate' => 'string',
            'sellerOrderNumber' => 'string',
            'invoiceDocument' => 'string',
            'invoiceDocumentFormat' => 'pdf',
            'invoiceType' => 'BOLETA',
            'operatorCode' => 'FACL',
            'invoiceLines' => [
                [
                    'sellerOrderLineId' => 'string',
                ],
            ],
        ];
        $expectedDataString = '{"invoiceNumber":0,"invoiceDate":"string","sellerOrderNumber":"string","invoiceDocument":"string","invoiceDocumentFormat":"pdf","invoiceType":"BOLETA","operatorCode":"FACL","invoiceLines":[{"sellerOrderLineId":"string"}]}';
        HandleResponse::validateJsonResponse($json);

        $this->assertEquals($expectedMessage, $result->getMessage());
        $this->assertEquals($expectedData, $result->getData());
        $this->assertEquals($expectedDataString, $result->getDataToString());
    }

    public function testItThrowAnExceptionWithAnEmptyJson(): void
    {
        $this->expectException(EmptyJsonException::class);

        $response = '{}';

        HandleResponse::parseJson($response);
    }

    public function testItThrowAnExceptionWithAnWrongJson(): void
    {
        $this->expectException(InvalidJsonException::class);

        $response = 'wrongJson';

        HandleResponse::parseJson($response);
    }

    public function testItThrowAnExceptionWithAnErrorJsonResponse(): void
    {
        $this->expectException(ErrorJsonResponseException::class);

        $json = $this->getSchema('Response/InvoiceDocumentErrorResponse.json');

        HandleResponse::validateJsonResponse($json);
    }
}

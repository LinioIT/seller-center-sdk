<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Unit\Validator;

use Linio\SellerCenter\Exception\InvalidXmlStructureException;
use Linio\SellerCenter\Validator\XmlStructureValidator;
use PHPUnit\Framework\TestCase;

class XmlStructureValidatorTest extends TestCase
{
    public function testThrowsInvalidXmlStructureException(): void
    {
        $this->expectException(InvalidXmlStructureException::class);
        $this->expectExceptionMessage('The xml structure is not valid for a Feed. The property RequestAction should exist.');

        $stringXml = '<?xml version="1.0" encoding="UTF-8"?>
            <Head>
                <RequestId>cb106552-87f3-450b-aa8b-412246a24b34</RequestId>
                <ResponseType>Xml</ResponseType>
                <Timestamp>2016-06-22T04:40:14+0200</Timestamp>
            </Head>
        ';

        $requiredFields = [
            'RequestId',
            'RequestAction',
            'ResponseType',
            'Timestamp',
        ];
        $xml = simplexml_load_string($stringXml);
        XmlStructureValidator::validateStructure($xml, 'Feed', $requiredFields);
    }
}

<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Order;

use Linio\Component\Util\Json;
use Linio\SellerCenter\Exception\InvalidXmlStructureException;
use Linio\SellerCenter\Factory\Xml\Order\TrackingCodeFactory;
use Linio\SellerCenter\LinioTestCase;
use Linio\SellerCenter\Model\Order\TrackingCode;

class TrackingCodeTest extends LinioTestCase
{
    protected $dispachId = '10f9780b-380c-4625-8024-b166ece74453';
    protected $trackingNumber = 'MOCKED_be834cdb-551f-4d03-bea7-38d255c4b13b';
    protected $schema = 'Order/TrackingCodeSucessResponse.xml';
    protected $jsonSchema = 'Order/TrackingCode.json';

    public function testItReturnsValidTrackingCode(): void
    {
        $simpleXml = simplexml_load_string($this->getSchema($this->schema))->Body;

        $trackingCode = TrackingCodeFactory::make($simpleXml);

        $this->assertInstanceOf(TrackingCode::class, $trackingCode);
        $this->assertEquals($simpleXml->TrackingCode->DispatchId, $trackingCode->getDispatchId());
        $this->assertEquals($simpleXml->TrackingCode->TrackingNumber, $trackingCode->getTrackingNumber());
    }

    /**
     * @dataProvider invalidXmlStructure
     */
    public function testItThrowsAExceptionWithoutAPropertyInTheXml(string $property): void
    {
        $this->expectException(InvalidXmlStructureException::class);

        $this->expectExceptionMessage(
            sprintf(
                'The xml structure is not valid for a TrackingCode. The property %s should exist.',
                $property
            )
        );

        $simpleXml = simplexml_load_string($this->getSchema($this->schema))->Body;

        unset($simpleXml->TrackingCode->{$property});

        TrackingCodeFactory::make($simpleXml);
    }

    public function testItReturnsAJsonRepresentation(): void
    {
        $simpleXml = simplexml_load_string($this->getSchema($this->schema))->Body;

        $address = TrackingCodeFactory::make($simpleXml);

        $expectedJson = Json::decode($this->getSchema($this->jsonSchema));

        $expectedJson['dispatchId'] = $this->dispachId;
        $expectedJson['trackingNumber'] = $this->trackingNumber;

        $this->assertJsonStringEqualsJsonString(Json::encode($expectedJson), Json::encode($address));
    }

    public function invalidXmlStructure(): array
    {
        return [
            ['DispatchId'],
            ['TrackingNumber'],
        ];
    }
}

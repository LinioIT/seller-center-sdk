<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Order;

use Linio\Component\Util\Json;
use Linio\SellerCenter\Exception\InvalidXmlStructureException;
use Linio\SellerCenter\Factory\Xml\Order\FailureReasonsFactory;
use Linio\SellerCenter\LinioTestCase;
use Linio\SellerCenter\Model\Order\FailureReason;
use SimpleXMLElement;

class FailureReasonTest extends LinioTestCase
{
    public function testItReturnsFailureReasons(): array
    {
        $reasonsData = [
            ['canceled', 'Sourcing team couldn\'t find items'],
            ['canceled', 'Wrong address'],
        ];

        $xml = new SimpleXMLElement('<Body><Reasons/></Body>');

        foreach ($reasonsData as $reasonData) {
            $reason = $xml->Reasons->addChild('Reason');
            $reason->addChild('Type', $reasonData[0]);
            $reason->addChild('Name', $reasonData[1]);
        }

        $reasons = FailureReasonsFactory::make($xml)->all();
        $this->assertContainsOnlyInstancesOf(FailureReason::class, $reasons);
        $this->assertCount(count($reasonsData), $reasons);

        foreach ($reasons as $index => $reason) {
            $this->assertEquals($reasonsData[$index][0], $reason->getType());
            $this->assertEquals($reasonsData[$index][1], $reason->getName());
        }

        return [$reasonsData, $reasons];
    }

    /**
     * @depends testItReturnsFailureReasons
     */
    public function testItIsJsonSerializable(array $reasonsProvider): void
    {
        $reasonData = $reasonsProvider[0];
        $reasons = $reasonsProvider[1];

        foreach ($reasons as $index => $reason) {
            $expectedJson = Json::encode([
                'type' => $reasonData[$index][0],
                'name' => $reasonData[$index][1],
            ]);

            $this->assertJsonStringEqualsJsonString($expectedJson, Json::encode($reason));
        }
    }

    public function testItThrowExceptionIfTypeIsMissing(): void
    {
        $this->expectException(InvalidXmlStructureException::class);
        $this->expectExceptionMessage('The xml structure is not valid for a FailureReason. The property Type should exist.');

        $reasonsData = [
            ['canceled', 'Sourcing team couldn\'t find items'],
            ['canceled', 'Wrong address'],
        ];

        $xml = new SimpleXMLElement('<Body><Reasons/></Body>');

        foreach ($reasonsData as $reasonData) {
            $reason = $xml->Reasons->addChild('Reason');
            $reason->addChild('Name', $reasonData[1]);
        }

        FailureReasonsFactory::make($xml);
    }

    public function testItThrowExceptionIfNameIsMissing(): void
    {
        $this->expectException(InvalidXmlStructureException::class);
        $this->expectExceptionMessage('The xml structure is not valid for a FailureReason. The property Name should exist.');

        $reasonsData = [
            ['canceled', 'Sourcing team couldn\'t find items'],
            ['canceled', 'Wrong address'],
        ];

        $xml = new SimpleXMLElement('<Body><Reasons/></Body>');

        foreach ($reasonsData as $reasonData) {
            $reason = $xml->Reasons->addChild('Reason');
            $reason->addChild('Type', $reasonData[0]);
        }

        FailureReasonsFactory::make($xml);
    }
}

<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Shipment;

use Linio\Component\Util\Json;
use Linio\SellerCenter\Exception\InvalidXmlStructureException;
use Linio\SellerCenter\Factory\Xml\Shipment\ShipmentProviderFactory;
use Linio\SellerCenter\LinioTestCase;

class ShipmentProviderTest extends LinioTestCase
{
    public function testItCreatesAnInstanceOfShipmentProvider(): array
    {
        $name = 'GDEX';
        $default = false;
        $apiIntegration = false;
        $cod = false;
        $trackingCodeValidationRegex = '^[0-9]{20}$/';
        $trackingCodeExample = '1234567889';
        $trackingUrl = 'http://intranet.gdexpress.com/official/etracking.php?capture={{{TRACKING_NR}}}';
        $enabledDeliveryOptions = ['express'];

        $xml = simplexml_load_string('<ShipmentProvider>
            <Name>' . $name . '</Name>
            <Default>' . (int) $default . '</Default>
            <ApiIntegration>' . (int) $apiIntegration . '</ApiIntegration>
            <Cod>' . (int) $cod . '</Cod>
            <TrackingCodeValidationRegex>' . $trackingCodeValidationRegex . '</TrackingCodeValidationRegex>
            <TrackingCodeExample>' . $trackingCodeExample . '</TrackingCodeExample>
            <TrackingUrl>' . $trackingUrl . '</TrackingUrl>
            <EnabledDeliveryOptions/>
          </ShipmentProvider>');

        foreach ($enabledDeliveryOptions as $deliveryOption) {
            $xml->EnabledDeliveryOptions->addChild('DeliveryOption', $deliveryOption);
        }

        $shipmentProvider = ShipmentProviderFactory::make($xml);

        $this->assertEquals($name, $shipmentProvider->getName());
        $this->assertEquals($default, $shipmentProvider->getDefault());
        $this->assertEquals($apiIntegration, $shipmentProvider->getApiIntegration());
        $this->assertEquals($cod, $shipmentProvider->getCod());
        $this->assertEquals($trackingCodeValidationRegex, $shipmentProvider->getTrackingCodeValidationRegex());
        $this->assertEquals($trackingCodeExample, $shipmentProvider->getTrackingCodeExample());
        $this->assertEquals($trackingUrl, $shipmentProvider->getTrackingUrl());
        $this->assertNull($shipmentProvider->getTrackingCodeSetOnStep());

        $this->assertNotEmpty($shipmentProvider->getEnabledDeliveryOptions());
        $this->assertEquals($enabledDeliveryOptions, $shipmentProvider->getEnabledDeliveryOptions());
        foreach ($shipmentProvider->getEnabledDeliveryOptions() as $index => $deliveryOption) {
            $this->assertSame($enabledDeliveryOptions[$index], $deliveryOption);
        }

        return [$xml, $shipmentProvider, $enabledDeliveryOptions];
    }

    /**
     * @depends testItCreatesAnInstanceOfShipmentProvider
     */
    public function testItReturnAJsonRepresentation(array $data): void
    {
        $simpleXml = $data[0];
        $shipmentProvider = $data[1];
        $enabledDeliveryOptions = $data[2];

        $json = Json::encode($shipmentProvider);

        $expectedJson = sprintf(
            '{"name":"%s","default":%s,"apiIntegration":false,"cod":%s,"trackingCodeValidationRegex":"%s","trackingCodeExample":"%s","trackingUrl":"%s","trackingCodeSetOnStep":null,"enabledDeliveryOptions":%s}',
            $simpleXml->Name,
            ((string) $simpleXml->Default) == '1' ? 'true' : 'false',
            ((string) $simpleXml->Cod) == '1' ? 'true' : 'false',
            $simpleXml->TrackingCodeValidationRegex,
            $simpleXml->TrackingCodeExample,
            $simpleXml->TrackingUrl,
            Json::encode($enabledDeliveryOptions)
        );

        $this->assertJsonStringEqualsJsonString($expectedJson, $json);
    }

    public function testItThrowsExceptionWhenNameIsMissing(): void
    {
        $this->expectException(InvalidXmlStructureException::class);
        $this->expectExceptionMessage('The xml structure is not valid for a ShipmentProvider. The property Name should exist.');

        $xml = simplexml_load_string(
            '<ShipmentProvider>
            <Default>0</Default>
            <ApiIntegration>0</ApiIntegration>
            <Cod>0</Cod>
            <TrackingCodeValidationRegex>/^[0-9]{20}$/</TrackingCodeValidationRegex>
            <TrackingCodeExample>12345678901234567890</TrackingCodeExample>
            <TrackingUrl>http://intranet.gdexpress.com/official/etracking.php?capture={{{TRACKING_NR}}}</TrackingUrl>
            <EnabledDeliveryOptions>
              <DeliveryOption>express</DeliveryOption>
              <DeliveryOption>standard</DeliveryOption>
              <DeliveryOption>economy</DeliveryOption>
            </EnabledDeliveryOptions>
          </ShipmentProvider>'
        );

        ShipmentProviderFactory::make($xml);
    }
}

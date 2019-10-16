<?php

declare(strict_types=1);

namespace Linio\SellerCenter;

use Linio\SellerCenter\Application\Configuration;
use Linio\SellerCenter\Model\Shipment\ShipmentProvider;

class ShipmentManagerTest extends LinioTestCase
{
    use ClientHelper;

    public function testItReturnsArrayOfShipmentProvider(): void
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>
                <SuccessResponse>
                  <Head>
                    <RequestId></RequestId>
                    <RequestAction>GetShipmentProviders</RequestAction>
                    <ResponseType>ShipmentProvider</ResponseType>
                    <Timestamp>2013-08-27T14:44:13+0000</Timestamp>
                  </Head>
                  <Body>
                    <ShipmentProviders>
                      <ShipmentProvider>
                        <Name>GDEX</Name>
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
                      </ShipmentProvider>
                    </ShipmentProviders>
                  </Body>
                </SuccessResponse>';

        $client = $this->createClientWithResponse($xml);

        $parameters = $this->getParameters();
        $configuration = new Configuration($parameters['key'], $parameters['username'], $parameters['endpoint'], $parameters['version']);

        $sdk = new SellerCenterSdk($configuration, $client);

        $shipmentProviders = $sdk->shipment()->getShipmentProviders();

        $this->assertCount(1, $shipmentProviders);
        $this->assertContainsOnlyInstancesOf(ShipmentProvider::class, $shipmentProviders);
    }
}

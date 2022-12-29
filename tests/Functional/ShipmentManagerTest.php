<?php

declare(strict_types=1);

namespace Linio\SellerCenter;

use Linio\SellerCenter\Model\Shipment\ShipmentProvider;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Log\LoggerInterface;

class ShipmentManagerTest extends LinioTestCase
{
    use ClientHelper;

    /**
     * @var ObjectProphecy
     */
    protected $logger;

    public function prepareLogTest(bool $debug): void
    {
        $this->logger = $this->prophesize(LoggerInterface::class);

        $this->logger->debug(
            Argument::type('string'),
            Argument::type('array')
        )->shouldBeCalled();

        if (!$debug) {
            $this->logger->debug(
                Argument::type('string'),
                Argument::type('array')
            )->shouldNotBeCalled();
        }
    }

    public function testItReturnsArrayOfShipmentProvider(): void
    {
        $sdkClient = $this->getSdkClient($this->getSchema('Order/GetShipmentProvidersSuccessResponse.xml'));

        $shipmentProviders = $sdkClient->shipment()->getShipmentProviders();

        $this->assertCount(1, $shipmentProviders);
        $this->assertContainsOnlyInstancesOf(ShipmentProvider::class, $shipmentProviders);
    }

    /**
     * @dataProvider debugParameter
     */
    public function testItLogsDependingOnDebugParamWhenGetShipmentProvidersSuccessResponse(bool $debug): void
    {
        $this->prepareLogTest($debug);
        $sdkClient = $this->getSdkClient(
            $this->getSchema('Order/GetShipmentProvidersSuccessResponse.xml'),
            $this->logger
        );

        $sdkClient->shipment()->getShipmentProviders($debug);
    }

    public function debugParameter()
    {
        return [
            [false],
            [true],
        ];
    }
}

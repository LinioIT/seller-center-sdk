<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Service;

use Linio\SellerCenter\Application\Configuration;
use Linio\SellerCenter\Application\Parameters;
use Linio\SellerCenter\Contract\ClientInterface;
use Linio\SellerCenter\LinioTestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Psr\Log\Test\TestLogger;
use ReflectionClass;

class ShipmentManagerTest extends LinioTestCase
{
    use ProphecyTrait;

    public function testItReturnsTheLoggerWhenIsSet(): void
    {
        $configuration = $this->prophesize(Configuration::class);
        $client = $this->prophesize(ClientInterface::class);
        $logger = $this->prophesize(TestLogger::class);
        $parameters = $this->prophesize(Parameters::class);

        $shipmentManager = new ShipmentManager($configuration->reveal(), $client->reveal(), $parameters->reveal(), $logger->reveal());

        $rs = new ReflectionClass(ShipmentManager::class);
        $property = $rs->getProperty('logger');
        $property->setAccessible(true);

        $setted = $property->getValue($shipmentManager);

        $this->assertInstanceOf(TestLogger::class, $setted);
    }
}

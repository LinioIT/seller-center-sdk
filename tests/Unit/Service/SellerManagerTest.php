<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Unit\Service;

use Linio\SellerCenter\Application\Configuration;
use Linio\SellerCenter\Application\Parameters;
use Linio\SellerCenter\Contract\ClientInterface;
use Linio\SellerCenter\LinioTestCase;
use Linio\SellerCenter\Service\SellerManager;
use Psr\Log\Test\TestLogger;
use ReflectionClass;

class SellerManagerTest extends LinioTestCase
{
    public function testReturnsASellerManager(): void
    {
        $configuration = $this->prophesize(Configuration::class);
        $client = $this->prophesize(ClientInterface::class);
        $parameters = $this->prophesize(Parameters::class);
        $logger = $this->prophesize(TestLogger::class);

        $sellerManager = new SellerManager(
            $configuration->reveal(),
            $client->reveal(),
            $parameters->reveal(),
            $logger->reveal()
        );
        $this->assertInstanceOf(SellerManager::class, $sellerManager);
    }

    public function testItReturnsTheLoggerWhenIsSet(): void
    {
        $configuration = $this->prophesize(Configuration::class);
        $client = $this->prophesize(ClientInterface::class);
        $logger = $this->prophesize(TestLogger::class);
        $parameters = $this->prophesize(Parameters::class);

        $sellerManager = new SellerManager($configuration->reveal(), $client->reveal(), $parameters->reveal(), $logger->reveal());

        $reflectionClass = new ReflectionClass(SellerManager::class);
        $property = $reflectionClass->getProperty('logger');
        $property->setAccessible(true);

        $setted = $property->getValue($sellerManager);

        $this->assertInstanceOf(TestLogger::class, $setted);
    }
}

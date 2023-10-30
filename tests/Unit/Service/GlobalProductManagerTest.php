<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Unit\Service;

use Linio\SellerCenter\Application\Configuration;
use Linio\SellerCenter\Application\Parameters;
use Linio\SellerCenter\Contract\ClientInterface;
use Linio\SellerCenter\Service\Contract\ProductManagerInterface;
use Linio\SellerCenter\Service\GlobalProductManager;
use PHPUnit\Framework\TestCase;
use Psr\Log\Test\TestLogger;
use ReflectionClass;

class GlobalProductManagerTest extends TestCase
{
    public function testReturnsAGlobalProductManagerAndProductManagerInterface(): void
    {
        $configuration = $this->prophesize(Configuration::class);
        $client = $this->prophesize(ClientInterface::class);
        $parameters = $this->prophesize(Parameters::class);
        $logger = $this->prophesize(TestLogger::class);

        $globalProductManager = new GlobalProductManager(
            $configuration->reveal(),
            $client->reveal(),
            $parameters->reveal(),
            $logger->reveal()
        );
        $this->assertInstanceOf(GlobalProductManager::class, $globalProductManager);
        $this->assertInstanceOf(ProductManagerInterface::class, $globalProductManager);
    }

    public function testReturnsTheLoggerWhenIsSetted(): void
    {
        $configuration = $this->prophesize(Configuration::class);
        $client = $this->prophesize(ClientInterface::class);
        $parameters = $this->prophesize(Parameters::class);
        $logger = $this->prophesize(TestLogger::class);

        $globalProductManager = new GlobalProductManager(
            $configuration->reveal(),
            $client->reveal(),
            $parameters->reveal(),
            $logger->reveal()
        );
        $this->assertInstanceOf(ProductManagerInterface::class, $globalProductManager);
        $reflectionClass = new ReflectionClass(GlobalProductManager::class);
        $property = $reflectionClass->getProperty('logger');
        $property->setAccessible(true);

        $setted = $property->getValue($globalProductManager);

        $this->assertInstanceOf(TestLogger::class, $setted);
    }
}

<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Unit\Service;

use Linio\SellerCenter\Application\Configuration;
use Linio\SellerCenter\Application\Parameters;
use Linio\SellerCenter\Contract\ClientInterface;
use Linio\SellerCenter\LinioTestCase;
use Linio\SellerCenter\Service\Contract\ProductManagerInterface;
use Linio\SellerCenter\Service\ProductManager;
use Psr\Log\Test\TestLogger;

class ProductManagerTest extends LinioTestCase
{
    public function testReturnsAProductManagerAndProductManagerInterface(): void
    {
        $configuration = $this->prophesize(Configuration::class);
        $client = $this->prophesize(ClientInterface::class);
        $parameters = $this->prophesize(Parameters::class);
        $logger = $this->prophesize(TestLogger::class);

        $productManager = new ProductManager(
            $configuration->reveal(),
            $client->reveal(),
            $parameters->reveal(),
            $logger->reveal()
        );
        $this->assertInstanceOf(ProductManager::class, $productManager);
        $this->assertInstanceOf(ProductManagerInterface::class, $productManager);
    }

    public function testReturnsTheLoggerWhenIsSetted(): void
    {
        $configuration = $this->prophesize(Configuration::class);
        $client = $this->prophesize(ClientInterface::class);
        $parameters = $this->prophesize(Parameters::class);
        $logger = $this->prophesize(TestLogger::class);

        $productManager = new ProductManager(
            $configuration->reveal(),
            $client->reveal(),
            $parameters->reveal(),
            $logger->reveal()
        );

        $reflectionClass = new \ReflectionClass(ProductManager::class);
        $property = $reflectionClass->getProperty('logger');
        $property->setAccessible(true);

        $setted = $property->getValue($productManager);

        $this->assertInstanceOf(TestLogger::class, $setted);
    }
}

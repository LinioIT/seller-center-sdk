<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Unit\Service;

use Linio\SellerCenter\Application\Configuration;
use Linio\SellerCenter\Application\Parameters;
use Linio\SellerCenter\Contract\ClientInterface;
use Linio\SellerCenter\Service\ProductManager;
use PHPUnit\Framework\TestCase;
use Psr\Log\Test\TestLogger;
use ReflectionClass;

class ProductManagerTest extends TestCase
{
    public function testReturnsTheTheLoggerWhenIsSetted(): void
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

        $reflectionClass = new ReflectionClass(ProductManager::class);
        $property = $reflectionClass->getProperty('logger');
        $property->setAccessible(true);

        $setted = $property->getValue($productManager);

        $this->assertInstanceOf(TestLogger::class, $setted);
    }
}

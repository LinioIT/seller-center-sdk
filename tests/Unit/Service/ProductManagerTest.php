<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Unit\Service;

use Linio\SellerCenter\Application\Configuration;
use Linio\SellerCenter\Application\Parameters;
use Linio\SellerCenter\Service\ProductManager;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
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
        $requestFactory = $this->prophesize(RequestFactoryInterface::class);
        $streamFactory = $this->prophesize(StreamFactoryInterface::class);

        $productManager = new ProductManager(
            $configuration->reveal(),
            $client->reveal(),
            $parameters->reveal(),
            $logger->reveal(),
            $requestFactory->reveal(),
            $streamFactory->reveal()
        );

        $reflectionClass = new ReflectionClass(ProductManager::class);
        $property = $reflectionClass->getProperty('logger');
        $property->setAccessible(true);

        $setted = $property->getValue($productManager);

        $this->assertInstanceOf(TestLogger::class, $setted);
    }
}

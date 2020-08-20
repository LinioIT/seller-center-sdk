<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Unit\Service;

use Linio\SellerCenter\Application\Configuration;
use Linio\SellerCenter\Application\Parameters;
use Linio\SellerCenter\LinioTestCase;
use Linio\SellerCenter\Service\CategoryManager;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Log\Test\TestLogger;
use ReflectionClass;

class CategoryManagerTest extends LinioTestCase
{
    public function testItReturnsTheLoggerWhenIsSet(): void
    {
        $configuration = $this->prophesize(Configuration::class);
        $client = $this->prophesize(ClientInterface::class);
        $parameters = $this->prophesize(Parameters::class);
        $logger = $this->prophesize(TestLogger::class);
        $requestFactory = $this->prophesize(RequestFactoryInterface::class);
        $streamFactory = $this->prophesize(StreamFactoryInterface::class);

        $categoryManager = new CategoryManager(
            $configuration->reveal(),
            $client->reveal(),
            $parameters->reveal(),
            $logger->reveal(),
            $requestFactory->reveal(),
            $streamFactory->reveal()
        );

        $reflectionClass = new ReflectionClass(CategoryManager::class);
        $property = $reflectionClass->getProperty('logger');
        $property->setAccessible(true);

        $setted = $property->getValue($categoryManager);

        $this->assertInstanceOf(TestLogger::class, $setted);
    }
}

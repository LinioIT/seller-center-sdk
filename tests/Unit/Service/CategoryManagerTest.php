<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Unit\Service;

use Linio\SellerCenter\Application\Configuration;
use Linio\SellerCenter\Application\Parameters;
use Linio\SellerCenter\Contract\ClientInterface;
use Linio\SellerCenter\LinioTestCase;
use Linio\SellerCenter\Service\CategoryManager;
use Psr\Log\Test\TestLogger;
use ReflectionClass;

class CategoryManagerTest extends LinioTestCase
{
    public function testReturnsABrandManager(): void
    {
        $configuration = $this->prophesize(Configuration::class);
        $client = $this->prophesize(ClientInterface::class);
        $parameters = $this->prophesize(Parameters::class);
        $logger = $this->prophesize(TestLogger::class);

        $categoryManager = new CategoryManager(
            $configuration->reveal(),
            $client->reveal(),
            $parameters->reveal(),
            $logger->reveal()
        );
        $this->assertInstanceOf(CategoryManager::class, $categoryManager);
    }

    public function testItReturnsTheLoggerWhenIsSet(): void
    {
        $configuration = $this->prophesize(Configuration::class);
        $client = $this->prophesize(ClientInterface::class);
        $parameters = $this->prophesize(Parameters::class);
        $logger = $this->prophesize(TestLogger::class);

        $categoryManager = new CategoryManager($configuration->reveal(), $client->reveal(), $parameters->reveal(), $logger->reveal());

        $reflectionClass = new ReflectionClass(CategoryManager::class);
        $property = $reflectionClass->getProperty('logger');
        $property->setAccessible(true);

        $setted = $property->getValue($categoryManager);

        $this->assertInstanceOf(TestLogger::class, $setted);
    }
}

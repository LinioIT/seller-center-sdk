<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Unit\Service;

use GuzzleHttp\Client;
use Linio\SellerCenter\Application\Configuration;
use Linio\SellerCenter\Application\Parameters;
use Linio\SellerCenter\LinioTestCase;
use Linio\SellerCenter\Service\CategoryManager;
use Psr\Log\Test\TestLogger;
use ReflectionClass;

class CategoryManagerTest extends LinioTestCase
{
    public function testItReturnsTheTheLoggerWhenIsSet(): void
    {
        $configuration = $this->prophesize(Configuration::class);
        $client = $this->prophesize(Client::class);
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

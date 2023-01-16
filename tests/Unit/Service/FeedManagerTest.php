<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Unit\Service;

use Linio\SellerCenter\Application\Configuration;
use Linio\SellerCenter\Application\Parameters;
use Linio\SellerCenter\Contract\ClientInterface;
use Linio\SellerCenter\LinioTestCase;
use Linio\SellerCenter\Service\FeedManager;
use Psr\Log\Test\TestLogger;
use ReflectionClass;

class FeedManagerTest extends LinioTestCase
{
    public function testReturnsAFeedManager(): void
    {
        $configuration = $this->prophesize(Configuration::class);
        $client = $this->prophesize(ClientInterface::class);
        $parameters = $this->prophesize(Parameters::class);
        $logger = $this->prophesize(TestLogger::class);

        $feedManager = new FeedManager(
            $configuration->reveal(),
            $client->reveal(),
            $parameters->reveal(),
            $logger->reveal()
        );
        $this->assertInstanceOf(FeedManager::class, $feedManager);
    }

    public function testItReturnsTheLoggerWhenIsSet(): void
    {
        $configuration = $this->prophesize(Configuration::class);
        $client = $this->prophesize(ClientInterface::class);
        $logger = $this->prophesize(TestLogger::class);
        $parameters = $this->prophesize(Parameters::class);

        $brandManager = new FeedManager($configuration->reveal(), $client->reveal(), $parameters->reveal(), $logger->reveal());

        $reflectionClass = new ReflectionClass(FeedManager::class);
        $property = $reflectionClass->getProperty('logger');
        $property->setAccessible(true);

        $setted = $property->getValue($brandManager);

        $this->assertInstanceOf(TestLogger::class, $setted);
    }
}

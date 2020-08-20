<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Service;

use Linio\SellerCenter\Application\Configuration;
use Linio\SellerCenter\Application\Parameters;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Log\Test\TestLogger;
use ReflectionClass;

class DocumentManagerTest extends TestCase
{
    public function testItReturnsTheLoggerWhenIsSet(): void
    {
        $configuration = $this->prophesize(Configuration::class);
        $client = $this->prophesize(ClientInterface::class);
        $logger = $this->prophesize(TestLogger::class);
        $parameters = $this->prophesize(Parameters::class);
        $requestFactory = $this->prophesize(RequestFactoryInterface::class);
        $streamFactory = $this->prophesize(StreamFactoryInterface::class);

        $documentManager = new DocumentManager(
            $configuration->reveal(),
            $client->reveal(),
            $parameters->reveal(),
            $logger->reveal(),
            $requestFactory->reveal(),
            $streamFactory->reveal()
        );

        $reflectionClass = new ReflectionClass(DocumentManager::class);
        $property = $reflectionClass->getProperty('logger');
        $property->setAccessible(true);

        $setted = $property->getValue($documentManager);

        $this->assertInstanceOf(TestLogger::class, $setted);
    }
}

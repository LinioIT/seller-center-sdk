<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Service;

use GuzzleHttp\Client;
use Linio\SellerCenter\Application\Configuration;
use Linio\SellerCenter\Application\Parameters;
use PHPUnit\Framework\TestCase;
use Psr\Log\Test\TestLogger;
use ReflectionClass;

class DocumentManagerTest extends TestCase
{
    public function testItReturnsTheTheLoggerWhenIsSet(): void
    {
        $configuration = $this->prophesize(Configuration::class);
        $client = $this->prophesize(Client::class);
        $logger = $this->prophesize(TestLogger::class);
        $parameters = $this->prophesize(Parameters::class);

        $documentManager = new DocumentManager($configuration->reveal(), $client->reveal(), $parameters->reveal(), $logger->reveal());

        $reflectionClass = new ReflectionClass(DocumentManager::class);
        $property = $reflectionClass->getProperty('logger');
        $property->setAccessible(true);

        $setted = $property->getValue($documentManager);

        $this->assertInstanceOf(TestLogger::class, $setted);
    }
}

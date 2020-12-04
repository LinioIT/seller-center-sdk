<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Unit\Service;

use Linio\SellerCenter\Application\Configuration;
use Linio\SellerCenter\Application\Parameters;
use Linio\SellerCenter\Contract\ClientInterface;
use Linio\SellerCenter\LinioTestCase;
use Linio\SellerCenter\Service\QualityControlManager;
use Psr\Log\Test\TestLogger;
use ReflectionClass;
use ReflectionMethod;

class QualityControlManagerTest extends LinioTestCase
{
    public function testItReturnsTheTheLoggerWhenIsSet(): void
    {
        $configuration = $this->prophesize(Configuration::class);
        $client = $this->prophesize(ClientInterface::class);
        $logger = $this->prophesize(TestLogger::class);
        $parameters = $this->prophesize(Parameters::class);

        $qcManager = new QualityControlManager($configuration->reveal(), $client->reveal(), $parameters->reveal(), $logger->reveal());

        $reflectionClass = new ReflectionClass(QualityControlManager::class);
        $property = $reflectionClass->getProperty('logger');
        $property->setAccessible(true);

        $setted = $property->getValue($qcManager);

        $this->assertInstanceOf(TestLogger::class, $setted);
    }

    /**
     * @dataProvider parametersProvider
     */
    public function testAddsTheLimitAndTheOffsetIntoTheParameters($limit, $offset, $expectedLimit, $expectedOffset): void
    {
        $config = $this->getParameters();

        $method = new ReflectionMethod(QualityControlManager::class, 'setListDimensions');
        $method->setAccessible(true);

        $configuration = $this->prophesize(Configuration::class);
        $client = $this->prophesize(ClientInterface::class);
        $logger = $this->prophesize(TestLogger::class);
        $parameters = $this->prophesize(Parameters::class);

        $qcManager = new QualityControlManager($configuration->reveal(), $client->reveal(), $parameters->reveal(), $logger->reveal());

        $parameters = Parameters::fromBasics($config['username'], $config['version']);

        $expected = clone $parameters;
        $expected->set(['Limit' => $expectedLimit, 'Offset' => $expectedOffset]);

        $method->invokeArgs($qcManager, [&$parameters, $limit, $offset]);

        $this->assertSame($expected->all(), $parameters->all());
    }

    public function parametersProvider(): array
    {
        return [
            [0, 0, QualityControlManager::DEFAULT_LIMIT, QualityControlManager::DEFAULT_OFFSET],
            [-2, -1, QualityControlManager::DEFAULT_LIMIT, QualityControlManager::DEFAULT_OFFSET],
            [100, 0, 100, 0],
            [200, 100, 200, 100],
            [1, 1, 1, 1],
        ];
    }
}

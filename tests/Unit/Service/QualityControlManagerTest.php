<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Unit\Service;

use Linio\SellerCenter\Application\Configuration;
use Linio\SellerCenter\Application\Parameters;
use Linio\SellerCenter\LinioTestCase;
use Linio\SellerCenter\Service\QualityControlManager;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Log\Test\TestLogger;
use ReflectionClass;
use ReflectionMethod;

class QualityControlManagerTest extends LinioTestCase
{
    /**
     * @var ObjectProphecy
     */
    protected $configurationStub;

    /**
     * @var ObjectProphecy
     */
    protected $clientStub;

    /**
     * @var Parameters
     */
    protected $parametersStub;

    /**
     * @var TestLogger
     */
    protected $loggerStub;

    /**
     * @var ObjectProphecy
     */
    protected $requestFactory;

    /**
     * @var ObjectProphecy
     */
    protected $streamFactory;

    /**
     * @var QualityControlManager
     */
    private $qualityControlManager;

    public function setUp(): void
    {
        $this->configurationStub = new Configuration('foo', 'bar', 'baz');
        $this->clientStub = $this->prophesize(ClientInterface::class);
        $this->parametersStub = new Parameters();
        $this->loggerStub = new TestLogger();
        $this->requestFactory = $this->prophesize(RequestFactoryInterface::class);
        $this->streamFactory = $this->prophesize(StreamFactoryInterface::class);

        $this->qualityControlManager = new QualityControlManager(
            $this->configurationStub,
            $this->clientStub->reveal(),
            $this->parametersStub,
            $this->loggerStub,
            $this->requestFactory->reveal(),
            $this->streamFactory->reveal()
        );
    }

    public function testItReturnsTheLoggerWhenIsSet(): void
    {
        $reflectionClass = new ReflectionClass(QualityControlManager::class);
        $property = $reflectionClass->getProperty('logger');
        $property->setAccessible(true);

        $setted = $property->getValue($this->qualityControlManager);

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

        $parameters = Parameters::fromBasics($config['username'], $config['version']);

        $expected = clone $parameters;
        $expected->set(['Limit' => $expectedLimit, 'Offset' => $expectedOffset]);

        $method->invokeArgs($this->qualityControlManager, [&$parameters, $limit, $offset]);

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

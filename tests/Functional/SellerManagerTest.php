<?php

declare(strict_types=1);

namespace Linio\SellerCenter;

use Exception;
use Linio\SellerCenter\Model\Seller\Statistic;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Log\LoggerInterface;

class SellerManagerTest extends LinioTestCase
{
    use ClientHelper;

    /**
     * @var ObjectProphecy
     */
    protected $logger;

    public function prepareLogTest(bool $debug): void
    {
        $this->logger = $this->prophesize(LoggerInterface::class);

        $this->logger->debug(
            Argument::type('string'),
            Argument::type('array')
        )->shouldBeCalled();

        if (!$debug) {
            $this->logger->debug(
                Argument::type('string'),
                Argument::type('array')
            )->shouldNotBeCalled();
        }
    }

    public function testItReturnsStatistics(): void
    {
        $sdkClient = $this->getSdkClient($this->getSchema('Seller/Statistics.xml'));

        $result = $sdkClient->seller()->getStatistics();

        $this->assertInstanceOf(Statistic::class, $result);
    }

    public function testItThrowsAnExceptionWhenTheResponseIsAnError(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('E0125: Test Error');

        $body = $this->getSchema('Response/ErrorResponse.xml');

        $sdkClient = $this->getSdkClient($body, null, 400);

        $sdkClient->seller()->getStatistics();
    }

    /**
     * @dataProvider debugParameter
     */
    public function testItLogsDependingOnDebugParamWhenGetStatisticsSuccessResponse(bool $debug): void
    {
        $this->prepareLogTest($debug);
        $sdkClient = $this->getSdkClient(
            $this->getSchema('Seller/Statistics.xml'),
            $this->logger
        );

        $sdkClient->seller()->getStatistics($debug);
    }

    public function debugParameter()
    {
        return [
            [false],
            [true],
        ];
    }
}

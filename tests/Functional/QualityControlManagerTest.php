<?php

declare(strict_types=1);

namespace Linio\SellerCenter;

use InvalidArgumentException;
use Linio\SellerCenter\Model\QualityControl\QualityControl;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Log\LoggerInterface;

class QualityControlManagerTest extends LinioTestCase
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

    public function testItReturnsACollectionOfQualityControls(): void
    {
        $sdkClient = $this->getSdkClient($this->getSchema('QcStatus/QcStatusSuccessResponse.xml'));

        $result = $sdkClient->qualityControl()->getAllQcStatus();

        $this->assertIsArray($result);
        $this->assertCount(6, $result);
        $this->assertContainsOnlyInstancesOf(QualityControl::class, $result);
    }

    public function testItReturnsACollectionOfQualityControlsBySkuSellerList(): void
    {
        $sdkClient = $this->getSdkClient($this->getSchema('QcStatus/QcStatusSuccessResponse.xml'));

        $skuSellerList = [
            'TestProduct2030',
            'TestProduct2031',
            'TestProduct2032',
            'TestProduct2033',
            'TestProduct2034',
        ];

        $result = $sdkClient->qualityControl()->getQcStatusBySkuSellerList($skuSellerList);

        $this->assertIsArray($result);
        $this->assertContainsOnlyInstancesOf(QualityControl::class, $result);
    }

    public function testItThrowsExceptionWithANullSkuSellerList(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $sdkClient = $this->getSdkClient($this->getSchema('QcStatus/QcStatusSuccessResponse.xml'));

        $sdkClient->qualityControl()->getQcStatusBySkuSellerList([]);
    }

    /**
     * @dataProvider debugParameter
     */
    public function testItLogsDependingOnDebugParamWhenGetAllQcStatusSuccessResponse(bool $debug): void
    {
        $this->prepareLogTest($debug);
        $sdkClient = $this->getSdkClient($this->getSchema('QcStatus/QcStatusSuccessResponse.xml'), $this->logger);

        $sdkClient->qualityControl()->getAllQcStatus(
            100,
            100,
            $debug
        );
    }

    /**
     * @dataProvider debugParameter
     */
    public function testItLogsDependingOnDebugParamWhenGetQcStatusBySkuSellerListSuccessResponse(bool $debug): void
    {
        $this->prepareLogTest($debug);
        $sdkClient = $this->getSdkClient($this->getSchema('QcStatus/QcStatusSuccessResponse.xml'), $this->logger);

        $sdkClient->qualityControl()->getQcStatusBySkuSellerList(
            ['test-sku'],
            100,
            100,
            $debug
        );
    }

    public function debugParameter()
    {
        return [
            [false],
            [true],
        ];
    }
}

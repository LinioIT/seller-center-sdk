<?php

declare(strict_types=1);

namespace Linio\SellerCenter;

use Exception;
use Linio\SellerCenter\Model\Seller\Seller;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Log\LoggerInterface;

class GlobalSellerManagerTest extends LinioTestCase
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

    public function testItReturnsSeller(): void
    {
        $sdkClient = $this->getSdkClient($this->getSchema('Seller/GetSellerByUserSuccessResponse.xml'));

        $result = $sdkClient->globalSeller()->getSellerByUser();

        $this->assertInstanceOf(Seller::class, $result);
    }

    public function testItThrowsAnExceptionWhenTheResponseIsAnError(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('E0125: Test Error');

        $body = $this->getSchema('Response/ErrorResponse.xml');

        $sdkClient = $this->getSdkClient($body, null, 400);

        $sdkClient->globalSeller()->getSellerByUser();
    }

    /**
     * @dataProvider debugParameter
     */
    public function testItLogsDependingOnDebugParamWhenGetSellerByUserSuccessResponse(bool $debug): void
    {
        $this->prepareLogTest($debug);
        $sdkClient = $this->getSdkClient(
            $this->getSchema('Seller/GetSellerByUserSuccessResponse.xml'),
            $this->logger
        );

        $sdkClient->globalSeller()->getSellerByUser($debug);
    }

    public function debugParameter()
    {
        return [
            [false],
            [true],
        ];
    }
}

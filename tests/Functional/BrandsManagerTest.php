<?php

declare(strict_types=1);

namespace Linio\SellerCenter;

use Exception;
use Linio\SellerCenter\Model\Brand\Brand;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Log\LoggerInterface;

class BrandsManagerTest extends LinioTestCase
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

    public function testItReturnsACollectionOfBrands(): void
    {
        $body = $this->getSchema('Brand/BrandsSuccessResponse.xml');

        $sdkClient = $this->getSdkClient($body);

        $result = $sdkClient->brands()->getBrands();

        $this->assertIsArray($result);
        $this->assertContainsOnlyInstancesOf(Brand::class, $result);
    }

    public function testItThrowsAnExceptionWhenTheResponseIsAnError(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('E0125: Test Error');

        $body = '<?xml version="1.0" encoding="UTF-8"?>
        <ErrorResponse>
            <Head>
                <RequestAction>GetOrder</RequestAction>
                <ErrorType>Sender</ErrorType>
                <ErrorCode>125</ErrorCode>
                <ErrorMessage>E0125: Test Error</ErrorMessage>
            </Head>
            <Body/>
        </ErrorResponse>';

        $sdkClient = $this->getSdkClient($body, null, 400);

        $sdkClient->brands()->getBrands();
    }

    /**
     * @dataProvider debugParameter
     */
    public function testItLogsDependingOnDebugParamWhenGetBrandsSuccessResponse(bool $debug): void
    {
        $body = $this->getSchema('Brand/BrandsSuccessResponse.xml');
        $this->prepareLogTest($debug);
        $sdkClient = $this->getSdkClient($body, $this->logger);

        $sdkClient->brands()->getBrands($debug);
    }

    public function debugParameter()
    {
        return [
            [false],
            [true],
        ];
    }
}

<?php

declare(strict_types=1);

namespace Linio\SellerCenter;

use Exception;
use Linio\SellerCenter\Application\Configuration;
use Linio\SellerCenter\Model\Brand\Brand;

class BrandsManagerTest extends LinioTestCase
{
    use ClientHelper;

    public function testItReturnsACollectionOfBrands(): void
    {
        $body = '<?xml version="1.0" encoding="UTF-8"?>
                    <SuccessResponse>
                      <Head>
                        <RequestId/>
                        <RequestAction>GetBrands</RequestAction>
                        <ResponseType>Brands</ResponseType>
                        <Timestamp>2015-07-01T11:11:11+0000</Timestamp>
                      </Head>
                      <Body>
                        <Brands>
                          <Brand>
                            <BrandId>1</BrandId>
                            <Name>Commodore</Name>
                            <GlobalIdentifier>commodore</GlobalIdentifier>
                          </Brand>
                          <Brand>
                            <BrandId>2</BrandId>
                            <Name>Atari</Name>
                            <GlobalIdentifier/>
                          </Brand>
                        </Brands>
                      </Body>
                    </SuccessResponse>';

        $client = $this->createClientWithResponse($body);

        $parameters = $this->getParameters();
        $configuration = new Configuration($parameters['key'], $parameters['username'], $parameters['endpoint'], $parameters['version']);

        $sdkClient = new SellerCenterSdk($configuration, $client);

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

        $client = $this->createClientWithResponse($body, 400);

        $env = $this->getParameters();

        $configuration = new Configuration($env['key'], $env['username'], $env['endpoint'], $env['version']);

        $sdkClient = new SellerCenterSdk($configuration, $client);

        $sdkClient->brands()->getBrands();
    }
}

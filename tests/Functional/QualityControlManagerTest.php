<?php

declare(strict_types=1);

namespace Linio\SellerCenter;

use InvalidArgumentException;
use Linio\SellerCenter\Application\Configuration;
use Linio\SellerCenter\Model\QualityControl\QualityControl;

class QualityControlManagerTest extends LinioTestCase
{
    use ClientHelper;

    public function testItReturnsACollectionOfQualityControls(): void
    {
        $client = $this->createClientWithResponse($this->getResponse());

        $parameters = $this->getParameters();
        $configuration = new Configuration($parameters['key'], $parameters['username'], $parameters['endpoint'], $parameters['version']);

        $sdkClient = new SellerCenterSdk($configuration, $client);

        $result = $sdkClient->qualityControl()->getAllQcStatus();

        $this->assertIsArray($result);
        $this->assertCount(6, $result);
        $this->assertContainsOnlyInstancesOf(QualityControl::class, $result);
    }

    public function testItReturnsACollectionOfQualityControlsBySkuSellerList(): void
    {
        $client = $this->createClientWithResponse($this->getResponse());

        $env = $this->getParameters();
        $configuration = new Configuration($env['key'], $env['username'], $env['endpoint'], $env['version']);

        $sdkClient = new SellerCenterSdk($configuration, $client);

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

        $client = $this->createClientWithResponse($this->getResponse());

        $env = $this->getParameters();
        $configuration = new Configuration($env['key'], $env['username'], $env['endpoint'], $env['version']);

        $sdkClient = new SellerCenterSdk($configuration, $client);

        $sdkClient->qualityControl()->getQcStatusBySkuSellerList([]);
    }

    public function getResponse(): string
    {
        return '<?xml version="1.0" encoding="UTF-8"?>
                    <SuccessResponse>
                         <Head>
                              <RequestId/>
                              <RequestAction>GetQcStatus</RequestAction>
                              <ResponseType>State</ResponseType>
                              <Timestamp>2016-04-28T15:09:01+0200</Timestamp>
                         </Head>
                         <Body>
                              <Status>
                                   <State>
                                        <SellerSKU>TestProduct2030</SellerSKU>
                                        <Status>approved</Status>
                                        <DataChanged>1</DataChanged>
                                   </State>
                                   <State>
                                        <SellerSKU>TestProduct2031</SellerSKU>
                                        <Status>approved</Status>
                                        <DataChanged>1</DataChanged>
                                   </State>
                                   <State>
                                        <SellerSKU>TestProduct2032</SellerSKU>
                                        <Status>pending</Status>
                                   </State>
                                   <State>
                                        <SellerSKU>TestProduct2033</SellerSKU>
                                        <Status>pending</Status>
                                   </State>
                                   <State>
                                        <SellerSKU>TestProduct2034</SellerSKU>
                                        <Status>rejected</Status>
                                        <Reason>Wrong Description; Wrong Translation</Reason>
                                   </State>
                                   <State>
                                        <SellerSKU>TestProduct2035</SellerSKU>
                                        <Status>rejected</Status>
                                        <Reason>Price Not Reasonable; Image Corrupt</Reason>
                                   </State>
                              </Status>
                         </Body>
                    </SuccessResponse>';
    }
}

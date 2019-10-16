<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Brand;

use Linio\SellerCenter\Factory\Xml\Brand\BrandsFactory;
use Linio\SellerCenter\LinioTestCase;
use Linio\SellerCenter\Model\Brand\Brand;

class BrandsTest extends LinioTestCase
{
    public function testItFindsAndReturnsTheBrandById(): void
    {
        $response = $this->getResponseMock();
        $brands = BrandsFactory::make($response->Body);

        $brand = $brands->findById(2);

        $this->assertInstanceOf(Brand::class, $brand);
        $this->assertTrue($brand->getBrandId() == 2);
    }

    public function testItFindsAndReturnTheBrandByName(): void
    {
        $response = $this->getResponseMock();
        $brands = BrandsFactory::make($response->Body);

        $brand = $brands->searchByName('Commodore');

        $this->assertIsArray($brand);
        $this->assertContainsOnly(Brand::class, $brand);
        $this->assertTrue($brand[0]->getName() == 'Commodore');
    }

    public function testItFindsAndReturnTheBrandByGlobalIdentifier(): void
    {
        $response = $this->getResponseMock();
        $brands = BrandsFactory::make($response->Body);

        $brand = $brands->searchByGlobalIdentifier('commodore');

        $this->assertIsArray($brand);
        $this->assertContainsOnly(Brand::class, $brand);
        $this->assertTrue($brand[0]->getGlobalIdentifier() == 'commodore');
    }

    public function testItReturnsAnEmptyValueWhenNoBrandWasFound(): void
    {
        $response = $this->getResponseMock();
        $brands = BrandsFactory::make($response->Body);

        $brand = $brands->findById(4);

        $this->assertNull($brand);
    }

    public function getResponseMock($xml = null)
    {
        if (empty($xml)) {
            $xml = '<SuccessResponse>
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
        }

        return simplexml_load_string($xml);
    }
}

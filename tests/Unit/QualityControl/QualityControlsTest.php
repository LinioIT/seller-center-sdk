<?php

declare(strict_types=1);

namespace Linio\SellerCenter\QualityControl;

use Linio\SellerCenter\Factory\Xml\QualityControl\QualityControlsFactory;
use Linio\SellerCenter\LinioTestCase;
use Linio\SellerCenter\Model\QualityControl\QualityControl;

class QualityControlsTest extends LinioTestCase
{
    public function testItFindsAndReturnsTheQualityControlBySellerSku(): void
    {
        $response = $this->getResponseMock();
        $qualityControls = QualityControlsFactory::make($response->Body);

        $qualityControl = $qualityControls->findBySellerSku('TestProduct2033');

        $this->assertInstanceOf(QualityControl::class, $qualityControl);
        $this->assertCount(6, $qualityControls->all());
        $this->assertSame($qualityControl->getSellerSku(), 'TestProduct2033');
    }

    public function testItFindsAndReturnsACollectionOfQualityControlsByStatus(): void
    {
        $response = $this->getResponseMock();
        $qualityControls = QualityControlsFactory::make($response->Body);

        $result = $qualityControls->searchByStatus('rejected');

        $this->assertContainsOnlyInstancesOf(QualityControl::class, $result);
        $this->assertCount(6, $qualityControls->all());
        $this->assertCount(2, $result);

        foreach ($result as $qualityControl) {
            $this->assertSame($qualityControl->getStatus(), 'rejected');
        }
    }

    public function testItReturnsAnEmptyValueWhenNoQualityControlWasFound(): void
    {
        $response = $this->getResponseMock();
        $qualityControls = QualityControlsFactory::make($response->Body);

        $qualityControl = $qualityControls->findBySellerSku('NoExist');

        $this->assertNull($qualityControl);
    }

    public function getResponseMock($xml = null)
    {
        if (empty($xml)) {
            $xml = '<SuccessResponse>
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
                                    <SellerSKU></SellerSKU>
                                    <Status>InvalidProduct</Status>
                               </State>
                               <State>
                                    <SellerSKU>InvalidProduct</SellerSKU>
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

        return simplexml_load_string($xml);
    }
}

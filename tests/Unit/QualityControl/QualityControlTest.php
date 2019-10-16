<?php

declare(strict_types=1);

namespace Linio\SellerCenter\QualityControl;

use Linio\Component\Util\Json;
use Linio\SellerCenter\Exception\EmptyArgumentException;
use Linio\SellerCenter\Exception\InvalidXmlStructureException;
use Linio\SellerCenter\Factory\Xml\QualityControl\QualityControlFactory;
use Linio\SellerCenter\LinioTestCase;
use Linio\SellerCenter\Model\QualityControl\QualityControl;
use SimpleXMLElement;

class QualityControlTest extends LinioTestCase
{
    public function testItReturnsTheValueWithEachAccessor(): void
    {
        $sellerSku = 'TestProduct2030';
        $status = 'approved';
        $dataChanged = 1;
        $reason = 'Price Not Reasonable; Image Corrupt';

        $simpleXml = simplexml_load_string(sprintf('<QualityControlEndpointFactory>
                            <SellerSKU>TestProduct2030</SellerSKU>
                            <Status>approved</Status>
                            <DataChanged>1</DataChanged>
                            <Reason>Price Not Reasonable; Image Corrupt</Reason>
                          </QualityControlEndpointFactory>', $sellerSku, $status, $dataChanged, $reason));

        $qualityControl = QualityControlFactory::make($simpleXml);

        $this->assertEquals($qualityControl->getSellerSku(), $sellerSku);
        $this->assertEquals($qualityControl->getStatus(), $status);
        $this->assertEquals($qualityControl->getDataChanged(), $dataChanged);
        $this->assertEquals($qualityControl->getReason(), $reason);
    }

    public function testItThrowsAnExceptionAIfTheSellerSkuIsNull(): void
    {
        $this->expectException(EmptyArgumentException::class);

        $this->expectExceptionMessage('The parameter SellerSku should not be null.');

        new QualityControl('', 'approved', null, null);
    }

    public function testItThrowsAnExceptionIfTheNameIsNull(): void
    {
        $this->expectException(EmptyArgumentException::class);

        $this->expectExceptionMessage('The parameter Status should not be null.');

        new QualityControl('TestProduct2030', '', null, null);
    }

    public function testItThrowsAExceptionWithoutASellerSkuInTheXml(): void
    {
        $this->expectException(InvalidXmlStructureException::class);

        $this->expectExceptionMessage('The xml structure is not valid for a QcStatus. The property SellerSku should exist.');

        $xml = '<QualityControlEndpointFactory>
                    <Status>approved</Status>
                    <DataChanged>1</DataChanged>
                    <Reason>Price Not Reasonable; Image Corrupt</Reason>
                </QualityControlEndpointFactory>';

        QualityControlFactory::make(new SimpleXMLElement($xml));
    }

    public function testItThrowsAExceptionWithoutAStatusInTheXml(): void
    {
        $this->expectException(InvalidXmlStructureException::class);

        $this->expectExceptionMessage('The xml structure is not valid for a QcStatus. The property Status should exist.');

        $xml = '<QualityControlEndpointFactory>
                    <SellerSKU>TestProduct2030</SellerSKU>
                    <DataChanged>1</DataChanged>
                    <Reason>Price Not Reasonable; Image Corrupt</Reason>
                </QualityControlEndpointFactory>';

        QualityControlFactory::make(new SimpleXMLElement($xml));
    }

    public function testItReturnsAJsonRepresentation(): void
    {
        $sellerSku = 'TestProduct2030';
        $status = 'approved';
        $dataChanged = true;
        $reason = 'Price Not Reasonable; Image Corrupt';

        $simpleXml = simplexml_load_string(sprintf('<QualityControlEndpointFactory>
                            <SellerSKU>%s</SellerSKU>
                            <Status>%s</Status>
                            <DataChanged>%s</DataChanged>
                            <Reason>%s</Reason>
                          </QualityControlEndpointFactory>', $sellerSku, $status, (int) $dataChanged, $reason));

        $qualityControl = QualityControlFactory::make($simpleXml);

        $expectedJson = sprintf(
            '{"sellerSku": "%s", "status": "%s", "dataChanged": %s, "reason": "%s"}',
            $sellerSku,
            $status,
            $dataChanged ? 'true' : 'false',
            $reason
        );
        $this->assertJsonStringEqualsJsonString($expectedJson, Json::encode($qualityControl));
    }
}

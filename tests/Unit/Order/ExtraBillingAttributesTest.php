<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Order;

use Linio\Component\Util\Json;
use Linio\SellerCenter\Exception\InvalidXmlStructureException;
use Linio\SellerCenter\Factory\Xml\Order\ExtraBillingAttributesFactory;
use Linio\SellerCenter\LinioTestCase;
use Linio\SellerCenter\Model\Order\ExtraBillingAttributes;

class ExtraBillingAttributesTest extends LinioTestCase
{
    protected $legalId = '77656276-9';
    protected $fiscalPerson = 'business';
    protected $documentType = 'RUT';
    protected $receiverRegion = 'METROPOLITANA DE SANTIAGO';
    protected $receiverAddress = 'JOSE MANUEL INFANTE 1155, 902 PROVIDENCIA';
    protected $receiverPostcode = '-';
    protected $receiverLegalName = 'COMERCIALIZADORA VIPAZ SPA';
    protected $receiverMunicipality = 'PROVIDENCIA';
    protected $receiverTypeRegimen = '475201 - VENTA AL POR MENOR DE ARTÍCULOS DE FERRETERÍA Y MATERIALES DE CONSTRUCCIÓN';
    protected $customerVerifierDigit = '9';
    protected $receiverLocality = 'PROVIDENCIA';
    protected $receiverEmail = 'comercializadora.vipaz@gmail.com';
    protected $receiverPhonenumber = '+56999100109';

    public function testItReturnsValidAddress(): void
    {
        $simpleXml = simplexml_load_string($this->createXmlStringForBillingInformation());

        $billingInformation = ExtraBillingAttributesFactory::make($simpleXml);

        $this->assertInstanceOf(ExtraBillingAttributes::class, $billingInformation);
        $this->assertEquals($simpleXml->LegalId, $billingInformation->getLegalId());
        $this->assertEquals($simpleXml->FiscalPerson, $billingInformation->getFiscalPerson());
        $this->assertEquals($simpleXml->DocumentType, $billingInformation->getDocumentType());
        $this->assertEquals($simpleXml->ReceiverRegion, $billingInformation->getReceiverRegion());
        $this->assertEquals($simpleXml->ReceiverAddress, $billingInformation->getReceiverAddress());
        $this->assertEquals($simpleXml->ReceiverPostcode, $billingInformation->getReceiverPostCode());
        $this->assertEquals($simpleXml->ReceiverLegalName, $billingInformation->getReceiverLegalName());
        $this->assertEquals($simpleXml->ReceiverMunicipality, $billingInformation->getReceiverMunicipality());
        $this->assertEquals($simpleXml->ReceiverTypeRegimen, $billingInformation->getReceiverTypeRegimen());
        $this->assertEquals($simpleXml->CustomerVerifierDigit, $billingInformation->getCustomerVerifierDigit());
        $this->assertEquals($simpleXml->ReceiverLocality, $billingInformation->getReceiverLocality());
        $this->assertEquals($simpleXml->ReceiverEmail, $billingInformation->getReceiverEmail());
        $this->assertEquals($simpleXml->ReceiverPhonenumber, $billingInformation->getReceiverPhoneNumber());
    }

    /**
     * @dataProvider invalidXmlStructure
     */
    public function testItThrowsAExceptionWithoutAPropertyInTheXml(string $property): void
    {
        $this->expectException(InvalidXmlStructureException::class);

        $this->expectExceptionMessage(
            sprintf(
                'The xml structure is not valid for a BillingAttributes. The property %s should exist.',
                $property
            )
        );

        $simpleXml = simplexml_load_string($this->createXmlStringForBillingInformation());
        unset($simpleXml->{$property});

        ExtraBillingAttributesFactory::make($simpleXml);
    }

    public function testItReturnsAJsonRepresentation(): void
    {
        $xml = $this->createXmlStringForBillingInformation();

        $simpleXml = simplexml_load_string($xml);

        $billingInformation = ExtraBillingAttributesFactory::make($simpleXml);

        $expectedJson = Json::decode($this->getSchema('Order/ExtraBillingAttributes.json'));
        $expectedJson['legalId'] = $this->legalId;
        $expectedJson['fiscalPerson'] = $this->fiscalPerson;
        $expectedJson['documentType'] = $this->documentType;
        $expectedJson['receiverRegion'] = $this->receiverRegion;
        $expectedJson['receiverAddress'] = $this->receiverAddress;
        $expectedJson['receiverPostcode'] = $this->receiverPostcode;
        $expectedJson['receiverLegalName'] = $this->receiverLegalName;
        $expectedJson['receiverMunicipality'] = $this->receiverMunicipality;
        $expectedJson['receiverTypeRegimen'] = $this->receiverTypeRegimen;
        $expectedJson['customerVerifierDigit'] = $this->customerVerifierDigit;
        $expectedJson['receiverLocality'] = $this->receiverLocality;
        $expectedJson['receiverEmail'] = $this->receiverEmail;
        $expectedJson['receiverPhonenumber'] = $this->receiverPhonenumber;

        $this->assertJsonStringEqualsJsonString(Json::encode($expectedJson), Json::encode($billingInformation));
    }

    public function createXmlStringForBillingInformation(string $schema = 'Order/ExtraBillingAttributes.xml'): string
    {
        return sprintf(
            $this->getSchema($schema),
            $this->legalId,
            $this->fiscalPerson,
            $this->documentType,
            $this->receiverRegion,
            $this->receiverAddress,
            $this->receiverPostcode,
            $this->receiverLegalName,
            $this->receiverMunicipality,
            $this->receiverTypeRegimen,
            $this->customerVerifierDigit,
            $this->receiverLocality,
            $this->receiverEmail,
            $this->receiverPhonenumber
        );
    }

    public function invalidXmlStructure(): array
    {
        return [
            ['LegalId'],
            ['FiscalPerson'],
            ['DocumentType'],
            ['ReceiverRegion'],
            ['ReceiverAddress'],
            ['ReceiverPostcode'],
            ['ReceiverLegalName'],
            ['ReceiverMunicipality'],
            ['ReceiverTypeRegimen'],
            ['CustomerVerifierDigit'],
            ['ReceiverLocality'],
            ['ReceiverEmail'],
            ['ReceiverPhonenumber'],
        ];
    }
}

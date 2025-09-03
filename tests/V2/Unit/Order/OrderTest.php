<?php

declare(strict_types=1);

namespace Linio\SellerCenter\V2\Model;

use DateTimeImmutable;
use Linio\Component\Util\Json;
use Linio\SellerCenter\Exception\InvalidDomainException;
use Linio\SellerCenter\Exception\InvalidXmlStructureException;
use Linio\SellerCenter\V2\Factory\Xml\Order\OrderFactory;
use Linio\SellerCenter\LinioTestCase;
use Linio\SellerCenter\Model\Order\Address;
use Linio\SellerCenter\V2\Model\Order\Order;


class OrderTest extends LinioTestCase
{
    protected $orderId = 4632913;
    protected $customerFirstName = 'first_name+4632913';
    protected $customerLastName = 'last_name';
    protected $orderNumber = 204527353;
    protected $globalOrderNumber = 'd060396b-826f-4ea8-83c0-3c6714a61785';
    protected $paymentMethod = 'CashOnDelivery_Payment';
    protected $remarks = 'someRemark';
    protected $deliveryInfo = 'someDeliveryInfo';
    protected $price = 95800.00;
    protected $giftOption = true;
    protected $createdAt = '2018-07-17 00:32:37';
    protected $updatedAt = '2018-07-19 09:12:11';
    protected $addressUpdatedAt = '2018-07-17 05:32:37';

    protected $firstName = 'firstName';
    protected $lastName = 'Rocket';
    protected $address1 = 'address';
    protected $address2 = 'address2';
    protected $address3 = 'address3';
    protected $address4 = 'address4';
    protected $address5 = 'address5';
    protected $city = 'city';
    protected $postCode = '10117';
    protected $country = 'country';
    protected $legalId = '77656276-9';
    protected $fiscalPerson = 'business';
    protected $documentType = 'RUT';
    protected $receiverRegion = 'METROPOLITANA DE SANTIAGO';
    protected $receiverAddress = 'JOSE MANUEL INFANTE 1155, 902 PROVIDENCIA';
    protected $receiverPostcode = '97873';
    protected $receiverLegalName = 'COMERCIALIZADORA VIPAZ SPA';
    protected $receiverMunicipality = 'PROVIDENCIA';
    protected $receiverTypeRegimen = '475201 - VENTA AL POR MENOR DE ARTÍCULOS DE FERRETERÍA Y MATERIALES DE CONSTRUCCIÓN';
    protected $customerVerifierDigit = '9';
    protected $receiverLocality = 'PROVIDENCIA';
    protected $receiverEmail = 'comercializadora.vipaz@gmail.com';
    protected $receiverPhonenumber = '+56999100109';
    protected $nationalRegistrationNumber = '72201776';
    protected $itemsCount = 1;
    protected $promisedShippingTime = '2018-07-18 23:59:59';
    protected $statuses = ['pending', 'canceled'];
    protected $extraAttributes = 'Extra attributes';
    protected $operatorCode = 'facl';
    protected $shippingType = 'Dropshipping';
    protected $businessInvoiceRequired = true;
    protected $grandTotal = 95800;
    protected $productTotal = 95800;
    protected $taxAmount = 15295;
    protected $shippingFeeTotal = 0;
    protected $shippingTax = 0;
    protected $voucher = 0;
    protected $warehouseFacilityId = 'LINIO01';
    protected $warehouseSellerWarehouseId = 'WH01';

    public function testItReturnsValidOrder(): Order
    {
        $simpleXml = simplexml_load_string($this->createXmlStringForAOrder());

        $order = OrderFactory::make($simpleXml);

        $this->assertInstanceOf(Order::class, $order);

        $this->assertEquals((int) $simpleXml->OrderId, $order->getOrderId());
        $this->assertEquals((string) $simpleXml->CustomerFirstName, $order->getCustomerFirstName());
        $this->assertEquals((string) $simpleXml->CustomerLastName, $order->getCustomerLastName());
        $this->assertEquals((int) $simpleXml->OrderNumber, $order->getOrderNumber());
        $this->assertEquals((string) $simpleXml->PaymentMethod, $order->getPaymentMethod());
        $this->assertEquals((string) $simpleXml->Remarks, $order->getRemarks());
        $this->assertEquals((string) $simpleXml->DeliveryInfo, $order->getDeliveryInfo());
        $this->assertEquals((float) $simpleXml->Price, $order->getPrice());
        $this->assertTrue($order->getGiftOption());
        $this->assertEquals((string) $simpleXml->GiftMessage, $order->getGiftMessage());
        $this->assertEquals((string) $simpleXml->VoucherCode, $order->getVoucherCode());
        $this->assertInstanceOf(DateTimeImmutable::class, $order->getCreatedAt());
        $this->assertInstanceOf(DateTimeImmutable::class, $order->getUpdatedAt());
        $this->assertInstanceOf(DateTimeImmutable::class, $order->getAddressUpdatedAt());
        $this->assertInstanceOf(Address::class, $order->getAddressBilling());
        $this->assertInstanceOf(Address::class, $order->getAddressShipping());
        $this->assertEquals((string) $simpleXml->NationalRegistrationNumber, $order->getNationalRegistrationNumber());
        $this->assertEquals((int) $simpleXml->ItemsCount, $order->getItemsCount());
        $this->assertInstanceOf(DateTimeImmutable::class, $order->getPromisedShippingTime());
        $this->assertEquals((string) $simpleXml->ExtraAttributes, $order->getExtraAttributes());
        $this->assertSame((string) $simpleXml->Statuses->Status[0], $order->getStatuses()[0]);
        $this->assertEquals((string) $simpleXml->OperatorCode, $order->getOperatorCode());
        $this->assertEquals((string) $simpleXml->GrandTotal, $order->getGrandTotal());
        $this->assertEquals((string) $simpleXml->ProductTotal, $order->getProductTotal());
        $this->assertEquals((string) $simpleXml->TaxAmount, $order->getTaxAmount());
        $this->assertEquals((string) $simpleXml->ShippingFeeTotal, $order->getShippingFeeTotal());
        $this->assertEquals((string) $simpleXml->ShippingTax, $order->getShippingTax());
        $this->assertEquals((string) $simpleXml->Voucher, $order->getVoucher());

        return $order;
    }

    /**
     * @dataProvider invalidXmlStructure
     */
    public function testItThrowsAExceptionWithoutAPropertyInTheXml(string $property): void
    {
        $this->expectException(InvalidXmlStructureException::class);

        $this->expectExceptionMessage(
            sprintf(
                'The xml structure is not valid for a Order. The property %s should exist.',
                $property
            )
        );

        $simpleXml = simplexml_load_string($this->createXmlStringForAOrder());
        unset($simpleXml->{$property});
        OrderFactory::make($simpleXml);
    }

    public function testItThrowsAExceptionWithIncorrectOperatotCodeInTheXml(): void
    {
        $this->expectException(InvalidDomainException::class);

        $this->expectExceptionMessage('The parameter OperatorCode is invalid.');

        $simpleXml = simplexml_load_string($this->createXmlStringForAOrder());
        $simpleXml->OperatorCode = 'sope';
        OrderFactory::make($simpleXml);
    }

    /**
     * @dataProvider DataVariations
     */
    public function testItReturnsAJsonRepresentation(string $property): void
    {
        $simpleXml = simplexml_load_string($this->createXmlStringForAOrder());
        $simpleXml->OrderNumber = $this->{$property};

        $order = OrderFactory::make($simpleXml);

        $expectedJson = Json::decode($this->getSchemaV2('Order/Order.json'));
        $expectedJson['orderId'] = $this->orderId;
        $expectedJson['customerFirstName'] = $this->customerFirstName;
        $expectedJson['customerLastName'] = $this->customerLastName;
        $expectedJson['orderNumber'] = $this->{$property};
        $expectedJson['paymentMethod'] = $this->paymentMethod;
        $expectedJson['remarks'] = $this->remarks;
        $expectedJson['deliveryInfo'] = $this->deliveryInfo;
        $expectedJson['price'] = $this->price;
        $expectedJson['giftOption'] = $this->giftOption;
        $expectedJson['createdAt'] = $order->getCreatedAt();
        $expectedJson['updatedAt'] = $order->getUpdatedAt();
        $expectedJson['addressUpdatedAt'] = $order->getAddressUpdatedAt();
        $expectedJson['addressBilling']['firstName'] = $this->firstName;
        $expectedJson['addressBilling']['lastName'] = $this->lastName;
        $expectedJson['addressBilling']['address'] = $this->address1;
        $expectedJson['addressBilling']['address2'] = $this->address2;
        $expectedJson['addressBilling']['address3'] = $this->address3;
        $expectedJson['addressBilling']['address4'] = $this->address4;
        $expectedJson['addressBilling']['address5'] = $this->address5;
        $expectedJson['addressBilling']['city'] = $this->city;
        $expectedJson['addressBilling']['postCode'] = $this->postCode;
        $expectedJson['addressBilling']['country'] = $this->country;
        $expectedJson['addressShipping']['firstName'] = $this->firstName;
        $expectedJson['addressShipping']['lastName'] = $this->lastName;
        $expectedJson['addressShipping']['address'] = $this->address1;
        $expectedJson['addressShipping']['address2'] = $this->address2;
        $expectedJson['addressShipping']['address3'] = $this->address3;
        $expectedJson['addressShipping']['address4'] = $this->address4;
        $expectedJson['addressShipping']['address5'] = $this->address5;
        $expectedJson['addressShipping']['city'] = $this->city;
        $expectedJson['addressShipping']['postCode'] = $this->postCode;
        $expectedJson['addressShipping']['country'] = $this->country;
        $expectedJson['nationalRegistrationNumber'] = $this->nationalRegistrationNumber;
        $expectedJson['itemsCount'] = $this->itemsCount;
        $expectedJson['promisedShippingTime'] = $order->getPromisedShippingTime();
        $expectedJson['extraAttributes'] = $this->extraAttributes;
        $expectedJson['statuses'][0] = $this->statuses[0];
        $expectedJson['statuses'][1] = $this->statuses[1];
        $expectedJson['businessInvoiceRequired'] = $this->businessInvoiceRequired;
        $expectedJson['shippingType'] = $this->shippingType;
        $expectedJson['operatorCode'] = $this->operatorCode;
        $expectedJson['grandTotal'] = $this->grandTotal;
        $expectedJson['productTotal'] = $this->productTotal;
        $expectedJson['taxAmount'] = $this->taxAmount;
        $expectedJson['shippingFeeTotal'] = $this->shippingFeeTotal;
        $expectedJson['shippingTax'] = $this->shippingTax;
        $expectedJson['voucher'] = $this->voucher;
        $expectedJson['warehouse']['facilityId'] = $this->warehouseFacilityId;
        $expectedJson['warehouse']['sellerWarehouseId'] = $this->warehouseSellerWarehouseId;

        $this->assertJsonStringEqualsJsonString(Json::encode($expectedJson), Json::encode($order));
    }

    public function createXmlStringForAOrder(string $schema = 'Order/Order.xml'): string
    {
        return sprintf(
            $this->getSchemaV2($schema),
            $this->orderId,
            $this->customerFirstName,
            $this->customerLastName,
            $this->orderNumber,
            $this->paymentMethod,
            $this->remarks,
            $this->deliveryInfo,
            $this->price,
            $this->giftOption,
            $this->createdAt,
            $this->updatedAt,
            $this->addressUpdatedAt,
            $this->firstName,
            $this->lastName,
            $this->address1,
            $this->city,
            $this->postCode,
            $this->country,
            $this->firstName,
            $this->lastName,
            $this->address1,
            $this->city,
            $this->postCode,
            $this->country,
            $this->nationalRegistrationNumber,
            $this->itemsCount,
            $this->promisedShippingTime,
            $this->extraAttributes,
            $this->statuses[0],
            $this->statuses[1],
            $this->operatorCode,
            $this->grandTotal,
            $this->productTotal,
            $this->taxAmount,
            $this->shippingFeeTotal,
            $this->shippingTax,
            $this->voucher,
            $this->warehouseFacilityId,
            $this->warehouseSellerWarehouseId
        );
    }

    public function invalidXmlStructure(): array
    {
        return [
            ['OrderId'],
            ['CustomerFirstName'],
            ['CustomerLastName'],
            ['OrderNumber'],
            ['PaymentMethod'],
            ['Remarks'],
            ['DeliveryInfo'],
            ['Price'],
            ['GiftOption'],
            ['GiftMessage'],
            ['VoucherCode'],
            ['CreatedAt'],
            ['UpdatedAt'],
            ['AddressUpdatedAt'],
            ['AddressBilling'],
            ['AddressShipping'],
            ['ItemsCount'],
            ['PromisedShippingTime'],
            ['ExtraAttributes'],
            ['Statuses'],
        ];
    }

    public function dataVariations(): array
    {
        return [
            ['orderNumber'],
            ['globalOrderNumber'],
        ];
    }
}

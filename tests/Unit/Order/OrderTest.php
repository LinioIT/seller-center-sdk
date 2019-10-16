<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model;

use DateTimeImmutable;
use Linio\Component\Util\Json;
use Linio\SellerCenter\Exception\InvalidXmlStructureException;
use Linio\SellerCenter\Factory\Xml\Order\OrderFactory;
use Linio\SellerCenter\LinioTestCase;
use Linio\SellerCenter\Model\Order\Address;
use Linio\SellerCenter\Model\Order\Order;
use Linio\SellerCenter\Model\Order\OrderItem;
use Linio\SellerCenter\Model\Order\OrderItems;

class OrderTest extends LinioTestCase
{
    public function testItReturnsValidOrder(): void
    {
        $simpleXml = simplexml_load_string($this->getValidOrderXmlStructure());

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
    }

    public function testItThrowsAExceptionWithoutAOrderIdInTheXml(): void
    {
        $this->expectException(InvalidXmlStructureException::class);

        $this->expectExceptionMessage('The xml structure is not valid for a Order. The property OrderId should exist.');

        $simpleXml = simplexml_load_string(
            '<Order>
                    <CustomerFirstName>first_name+4632913</CustomerFirstName>
                    <CustomerLastName>last_name</CustomerLastName>
                    <OrderNumber>204527353</OrderNumber>
                    <PaymentMethod>CashOnDelivery_Payment</PaymentMethod>
                    <Remarks>someRemark</Remarks>
                    <DeliveryInfo>someDeliveryInfo</DeliveryInfo>
                    <Price>95800.00</Price>
                    <GiftOption>1</GiftOption>
                    <GiftMessage>someGiftMessage</GiftMessage>
                    <VoucherCode>someCode</VoucherCode>
                    <CreatedAt>2018-07-17 00:32:37</CreatedAt>
                    <UpdatedAt>2018-07-19 09:12:11</UpdatedAt>
                    <AddressUpdatedAt>2018-07-17 05:32:37</AddressUpdatedAt>
                    <AddressBilling></AddressBilling>
                    <AddressShipping></AddressShipping>
                    <NationalRegistrationNumber>72201776</NationalRegistrationNumber>
                    <ItemsCount>1</ItemsCount>
                    <PromisedShippingTime>2018-07-18 23:59:59</PromisedShippingTime>
                    <ExtraAttributes>{"color":"someJsonData", "condition":"someJsonData2"}</ExtraAttributes>
                    <Statuses></Statuses>
               </Order>'
        );

        OrderFactory::make($simpleXml);
    }

    public function testItThrowsAExceptionWithoutACustomerFirstNameInTheXml(): void
    {
        $this->expectException(InvalidXmlStructureException::class);

        $this->expectExceptionMessage('The xml structure is not valid for a Order. The property CustomerFirstName should exist.');

        $simpleXml = simplexml_load_string(
            '<Order>
                    <OrderId>4632913</OrderId>
                    <CustomerLastName>last_name</CustomerLastName>
                    <OrderNumber>204527353</OrderNumber>
                    <PaymentMethod>CashOnDelivery_Payment</PaymentMethod>
                    <Remarks>someRemark</Remarks>
                    <DeliveryInfo>someDeliveryInfo</DeliveryInfo>
                    <Price>95800.00</Price>
                    <GiftOption>1</GiftOption>
                    <GiftMessage>someGiftMessage</GiftMessage>
                    <VoucherCode>someCode</VoucherCode>
                    <CreatedAt>2018-07-17 00:32:37</CreatedAt>
                    <UpdatedAt>2018-07-19 09:12:11</UpdatedAt>
                    <AddressUpdatedAt>2018-07-17 05:32:37</AddressUpdatedAt>
                    <AddressBilling></AddressBilling>
                    <AddressShipping></AddressShipping>
                    <NationalRegistrationNumber>72201776</NationalRegistrationNumber>
                    <ItemsCount>1</ItemsCount>
                    <PromisedShippingTime>2018-07-18 23:59:59</PromisedShippingTime>
                    <ExtraAttributes>{"color":"someJsonData", "condition":"someJsonData2"}</ExtraAttributes>
                    <Statuses></Statuses>
               </Order>'
        );

        OrderFactory::make($simpleXml);
    }

    public function testItThrowsAExceptionWithoutACustomerLastNameInTheXml(): void
    {
        $this->expectException(InvalidXmlStructureException::class);

        $this->expectExceptionMessage('The xml structure is not valid for a Order. The property CustomerLastName should exist.');

        $simpleXml = simplexml_load_string(
            '<Order>
                    <OrderId>4632913</OrderId>
                    <CustomerFirstName>first_name+4632913</CustomerFirstName>
                    <OrderNumber>204527353</OrderNumber>
                    <PaymentMethod>CashOnDelivery_Payment</PaymentMethod>
                    <Remarks>someRemark</Remarks>
                    <DeliveryInfo>someDeliveryInfo</DeliveryInfo>
                    <Price>95800.00</Price>
                    <GiftOption>1</GiftOption>
                    <GiftMessage>someGiftMessage</GiftMessage>
                    <VoucherCode>someCode</VoucherCode>
                    <CreatedAt>2018-07-17 00:32:37</CreatedAt>
                    <UpdatedAt>2018-07-19 09:12:11</UpdatedAt>
                    <AddressUpdatedAt>2018-07-17 05:32:37</AddressUpdatedAt>
                    <AddressBilling></AddressBilling>
                    <AddressShipping></AddressShipping>
                    <NationalRegistrationNumber>72201776</NationalRegistrationNumber>
                    <ItemsCount>1</ItemsCount>
                    <PromisedShippingTime>2018-07-18 23:59:59</PromisedShippingTime>
                    <ExtraAttributes>{"color":"someJsonData", "condition":"someJsonData2"}</ExtraAttributes>
                    <Statuses></Statuses>
               </Order>'
        );

        OrderFactory::make($simpleXml);
    }

    public function testItThrowsAExceptionWithoutAOrderNumberInTheXml(): void
    {
        $this->expectException(InvalidXmlStructureException::class);

        $this->expectExceptionMessage('The xml structure is not valid for a Order. The property OrderNumber should exist.');

        $simpleXml = simplexml_load_string(
            '<Order>
                    <OrderId>4632913</OrderId>
                    <CustomerFirstName>first_name+4632913</CustomerFirstName>
                    <CustomerLastName>last_name</CustomerLastName>
                    <PaymentMethod>CashOnDelivery_Payment</PaymentMethod>
                    <Remarks>someRemark</Remarks>
                    <DeliveryInfo>someDeliveryInfo</DeliveryInfo>
                    <Price>95800.00</Price>
                    <GiftOption>1</GiftOption>
                    <GiftMessage>someGiftMessage</GiftMessage>
                    <VoucherCode>someCode</VoucherCode>
                    <CreatedAt>2018-07-17 00:32:37</CreatedAt>
                    <UpdatedAt>2018-07-19 09:12:11</UpdatedAt>
                    <AddressUpdatedAt>2018-07-17 05:32:37</AddressUpdatedAt>
                    <AddressBilling></AddressBilling>
                    <AddressShipping></AddressShipping>
                    <NationalRegistrationNumber>72201776</NationalRegistrationNumber>
                    <ItemsCount>1</ItemsCount>
                    <PromisedShippingTime>2018-07-18 23:59:59</PromisedShippingTime>
                    <ExtraAttributes>{"color":"someJsonData", "condition":"someJsonData2"}</ExtraAttributes>
                    <Statuses></Statuses>
               </Order>'
        );

        OrderFactory::make($simpleXml);
    }

    public function testItThrowsAExceptionWithoutAPaymentMethodInTheXml(): void
    {
        $this->expectException(InvalidXmlStructureException::class);

        $this->expectExceptionMessage('The xml structure is not valid for a Order. The property PaymentMethod should exist.');

        $simpleXml = simplexml_load_string(
            '<Order>
                    <OrderId>4632913</OrderId>
                    <CustomerFirstName>first_name+4632913</CustomerFirstName>
                    <CustomerLastName>last_name</CustomerLastName>
                    <OrderNumber>204527353</OrderNumber>
                    <Remarks>someRemark</Remarks>
                    <DeliveryInfo>someDeliveryInfo</DeliveryInfo>
                    <Price>95800.00</Price>
                    <GiftOption>1</GiftOption>
                    <GiftMessage>someGiftMessage</GiftMessage>
                    <VoucherCode>someCode</VoucherCode>
                    <CreatedAt>2018-07-17 00:32:37</CreatedAt>
                    <UpdatedAt>2018-07-19 09:12:11</UpdatedAt>
                    <AddressUpdatedAt>2018-07-17 05:32:37</AddressUpdatedAt>
                    <AddressBilling></AddressBilling>
                    <AddressShipping></AddressShipping>
                    <NationalRegistrationNumber>72201776</NationalRegistrationNumber>
                    <ItemsCount>1</ItemsCount>
                    <PromisedShippingTime>2018-07-18 23:59:59</PromisedShippingTime>
                    <ExtraAttributes>{"color":"someJsonData", "condition":"someJsonData2"}</ExtraAttributes>
                    <Statuses></Statuses>
               </Order>'
        );

        OrderFactory::make($simpleXml);
    }

    public function testItThrowsAExceptionWithoutARemarksInTheXml(): void
    {
        $this->expectException(InvalidXmlStructureException::class);

        $this->expectExceptionMessage('The xml structure is not valid for a Order. The property Remarks should exist.');

        $simpleXml = simplexml_load_string(
            '<Order>
                    <OrderId>4632913</OrderId>
                    <CustomerFirstName>first_name+4632913</CustomerFirstName>
                    <CustomerLastName>last_name</CustomerLastName>
                    <OrderNumber>204527353</OrderNumber>
                    <PaymentMethod>CashOnDelivery_Payment</PaymentMethod>
                    <DeliveryInfo>someDeliveryInfo</DeliveryInfo>
                    <Price>95800.00</Price>
                    <GiftOption>1</GiftOption>
                    <GiftMessage>someGiftMessage</GiftMessage>
                    <VoucherCode>someCode</VoucherCode>
                    <CreatedAt>2018-07-17 00:32:37</CreatedAt>
                    <UpdatedAt>2018-07-19 09:12:11</UpdatedAt>
                    <AddressUpdatedAt>2018-07-17 05:32:37</AddressUpdatedAt>
                    <AddressBilling></AddressBilling>
                    <AddressShipping></AddressShipping>
                    <NationalRegistrationNumber>72201776</NationalRegistrationNumber>
                    <ItemsCount>1</ItemsCount>
                    <PromisedShippingTime>2018-07-18 23:59:59</PromisedShippingTime>
                    <ExtraAttributes>{"color":"someJsonData", "condition":"someJsonData2"}</ExtraAttributes>
                    <Statuses></Statuses>
               </Order>'
        );

        OrderFactory::make($simpleXml);
    }

    public function testItThrowsAExceptionWithoutADeliveryInfoInTheXml(): void
    {
        $this->expectException(InvalidXmlStructureException::class);

        $this->expectExceptionMessage('The xml structure is not valid for a Order. The property DeliveryInfo should exist.');

        $simpleXml = simplexml_load_string(
            '<Order>
                    <OrderId>4632913</OrderId>
                    <CustomerFirstName>first_name+4632913</CustomerFirstName>
                    <CustomerLastName>last_name</CustomerLastName>
                    <OrderNumber>204527353</OrderNumber>
                    <PaymentMethod>CashOnDelivery_Payment</PaymentMethod>
                    <Remarks>someRemark</Remarks>
                    <Price>95800.00</Price>
                    <GiftOption>1</GiftOption>
                    <GiftMessage>someGiftMessage</GiftMessage>
                    <VoucherCode>someCode</VoucherCode>
                    <CreatedAt>2018-07-17 00:32:37</CreatedAt>
                    <UpdatedAt>2018-07-19 09:12:11</UpdatedAt>
                    <AddressUpdatedAt>2018-07-17 05:32:37</AddressUpdatedAt>
                    <AddressBilling></AddressBilling>
                    <AddressShipping></AddressShipping>
                    <NationalRegistrationNumber>72201776</NationalRegistrationNumber>
                    <ItemsCount>1</ItemsCount>
                    <PromisedShippingTime>2018-07-18 23:59:59</PromisedShippingTime>
                    <ExtraAttributes>{"color":"someJsonData", "condition":"someJsonData2"}</ExtraAttributes>
                    <Statuses></Statuses>
               </Order>'
        );

        OrderFactory::make($simpleXml);
    }

    public function testItThrowsAExceptionWithoutAPriceInTheXml(): void
    {
        $this->expectException(InvalidXmlStructureException::class);

        $this->expectExceptionMessage('The xml structure is not valid for a Order. The property Price should exist.');

        $simpleXml = simplexml_load_string(
            '<Order>
                    <OrderId>4632913</OrderId>
                    <CustomerFirstName>first_name+4632913</CustomerFirstName>
                    <CustomerLastName>last_name</CustomerLastName>
                    <OrderNumber>204527353</OrderNumber>
                    <PaymentMethod>CashOnDelivery_Payment</PaymentMethod>
                    <Remarks>someRemark</Remarks>
                    <DeliveryInfo>someDeliveryInfo</DeliveryInfo>
                    <GiftOption>1</GiftOption>
                    <GiftMessage>someGiftMessage</GiftMessage>
                    <VoucherCode>someCode</VoucherCode>
                    <CreatedAt>2018-07-17 00:32:37</CreatedAt>
                    <UpdatedAt>2018-07-19 09:12:11</UpdatedAt>
                    <AddressUpdatedAt>2018-07-17 05:32:37</AddressUpdatedAt>
                    <AddressBilling></AddressBilling>
                    <AddressShipping></AddressShipping>
                    <NationalRegistrationNumber>72201776</NationalRegistrationNumber>
                    <ItemsCount>1</ItemsCount>
                    <PromisedShippingTime>2018-07-18 23:59:59</PromisedShippingTime>
                    <ExtraAttributes>{"color":"someJsonData", "condition":"someJsonData2"}</ExtraAttributes>
                    <Statuses></Statuses>
               </Order>'
        );

        OrderFactory::make($simpleXml);
    }

    public function testItThrowsAExceptionWithoutAGiftOptionInTheXml(): void
    {
        $this->expectException(InvalidXmlStructureException::class);

        $this->expectExceptionMessage('The xml structure is not valid for a Order. The property GiftOption should exist.');

        $simpleXml = simplexml_load_string(
            '<Order>
                    <OrderId>4632913</OrderId>
                    <CustomerFirstName>first_name+4632913</CustomerFirstName>
                    <CustomerLastName>last_name</CustomerLastName>
                    <OrderNumber>204527353</OrderNumber>
                    <PaymentMethod>CashOnDelivery_Payment</PaymentMethod>
                    <Remarks>someRemark</Remarks>
                    <DeliveryInfo>someDeliveryInfo</DeliveryInfo>
                    <Price>95800.00</Price>
                    <GiftMessage>someGiftMessage</GiftMessage>
                    <VoucherCode>someCode</VoucherCode>
                    <CreatedAt>2018-07-17 00:32:37</CreatedAt>
                    <UpdatedAt>2018-07-19 09:12:11</UpdatedAt>
                    <AddressUpdatedAt>2018-07-17 05:32:37</AddressUpdatedAt>
                    <AddressBilling></AddressBilling>
                    <AddressShipping></AddressShipping>
                    <NationalRegistrationNumber>72201776</NationalRegistrationNumber>
                    <ItemsCount>1</ItemsCount>
                    <PromisedShippingTime>2018-07-18 23:59:59</PromisedShippingTime>
                    <ExtraAttributes>{"color":"someJsonData", "condition":"someJsonData2"}</ExtraAttributes>
                    <Statuses></Statuses>
               </Order>'
        );

        OrderFactory::make($simpleXml);
    }

    public function testItThrowsAExceptionWithoutAGiftMessageInTheXml(): void
    {
        $this->expectException(InvalidXmlStructureException::class);

        $this->expectExceptionMessage('The xml structure is not valid for a Order. The property GiftMessage should exist.');

        $simpleXml = simplexml_load_string(
            '<Order>
                    <OrderId>4632913</OrderId>
                    <CustomerFirstName>first_name+4632913</CustomerFirstName>
                    <CustomerLastName>last_name</CustomerLastName>
                    <OrderNumber>204527353</OrderNumber>
                    <PaymentMethod>CashOnDelivery_Payment</PaymentMethod>
                    <Remarks>someRemark</Remarks>
                    <DeliveryInfo>someDeliveryInfo</DeliveryInfo>
                    <Price>95800.00</Price>
                    <GiftOption>1</GiftOption>
                    <VoucherCode>someCode</VoucherCode>
                    <CreatedAt>2018-07-17 00:32:37</CreatedAt>
                    <UpdatedAt>2018-07-19 09:12:11</UpdatedAt>
                    <AddressUpdatedAt>2018-07-17 05:32:37</AddressUpdatedAt>
                    <AddressBilling></AddressBilling>
                    <AddressShipping></AddressShipping>
                    <NationalRegistrationNumber>72201776</NationalRegistrationNumber>
                    <ItemsCount>1</ItemsCount>
                    <PromisedShippingTime>2018-07-18 23:59:59</PromisedShippingTime>
                    <ExtraAttributes>{"color":"someJsonData", "condition":"someJsonData2"}</ExtraAttributes>
                    <Statuses></Statuses>
               </Order>'
        );

        OrderFactory::make($simpleXml);
    }

    public function testItThrowsAExceptionWithoutAVoucherCodeInTheXml(): void
    {
        $this->expectException(InvalidXmlStructureException::class);

        $this->expectExceptionMessage('The xml structure is not valid for a Order. The property VoucherCode should exist.');

        $simpleXml = simplexml_load_string(
            '<Order>
                    <OrderId>4632913</OrderId>
                    <CustomerFirstName>first_name+4632913</CustomerFirstName>
                    <CustomerLastName>last_name</CustomerLastName>
                    <OrderNumber>204527353</OrderNumber>
                    <PaymentMethod>CashOnDelivery_Payment</PaymentMethod>
                    <Remarks>someRemark</Remarks>
                    <DeliveryInfo>someDeliveryInfo</DeliveryInfo>
                    <Price>95800.00</Price>
                    <GiftOption>1</GiftOption>
                    <GiftMessage>someGiftMessage</GiftMessage>
                    <CreatedAt>2018-07-17 00:32:37</CreatedAt>
                    <UpdatedAt>2018-07-19 09:12:11</UpdatedAt>
                    <AddressUpdatedAt>2018-07-17 05:32:37</AddressUpdatedAt>
                    <AddressBilling></AddressBilling>
                    <AddressShipping></AddressShipping>
                    <NationalRegistrationNumber>72201776</NationalRegistrationNumber>
                    <ItemsCount>1</ItemsCount>
                    <PromisedShippingTime>2018-07-18 23:59:59</PromisedShippingTime>
                    <ExtraAttributes>{"color":"someJsonData", "condition":"someJsonData2"}</ExtraAttributes>
                    <Statuses></Statuses>
               </Order>'
        );

        OrderFactory::make($simpleXml);
    }

    public function testItThrowsAExceptionWithoutACreatedAtInTheXml(): void
    {
        $this->expectException(InvalidXmlStructureException::class);

        $this->expectExceptionMessage('The xml structure is not valid for a Order. The property CreatedAt should exist.');

        $simpleXml = simplexml_load_string(
            '<Order>
                    <OrderId>4632913</OrderId>
                    <CustomerFirstName>first_name+4632913</CustomerFirstName>
                    <CustomerLastName>last_name</CustomerLastName>
                    <OrderNumber>204527353</OrderNumber>
                    <PaymentMethod>CashOnDelivery_Payment</PaymentMethod>
                    <Remarks>someRemark</Remarks>
                    <DeliveryInfo>someDeliveryInfo</DeliveryInfo>
                    <Price>95800.00</Price>
                    <GiftOption>1</GiftOption>
                    <GiftMessage>someGiftMessage</GiftMessage>
                    <VoucherCode>someCode</VoucherCode>
                    <UpdatedAt>2018-07-19 09:12:11</UpdatedAt>
                    <AddressUpdatedAt>2018-07-17 05:32:37</AddressUpdatedAt>
                    <AddressBilling></AddressBilling>
                    <AddressShipping></AddressShipping>
                    <NationalRegistrationNumber>72201776</NationalRegistrationNumber>
                    <ItemsCount>1</ItemsCount>
                    <PromisedShippingTime>2018-07-18 23:59:59</PromisedShippingTime>
                    <ExtraAttributes>{"color":"someJsonData", "condition":"someJsonData2"}</ExtraAttributes>
                    <Statuses></Statuses>
               </Order>'
        );

        OrderFactory::make($simpleXml);
    }

    public function testItThrowsAExceptionWithoutAUpdatedAtInTheXml(): void
    {
        $this->expectException(InvalidXmlStructureException::class);

        $this->expectExceptionMessage('The xml structure is not valid for a Order. The property UpdatedAt should exist.');

        $simpleXml = simplexml_load_string(
            '<Order>
                    <OrderId>4632913</OrderId>
                    <CustomerFirstName>first_name+4632913</CustomerFirstName>
                    <CustomerLastName>last_name</CustomerLastName>
                    <OrderNumber>204527353</OrderNumber>
                    <PaymentMethod>CashOnDelivery_Payment</PaymentMethod>
                    <Remarks>someRemark</Remarks>
                    <DeliveryInfo>someDeliveryInfo</DeliveryInfo>
                    <Price>95800.00</Price>
                    <GiftOption>1</GiftOption>
                    <GiftMessage>someGiftMessage</GiftMessage>
                    <VoucherCode>someCode</VoucherCode>
                    <CreatedAt>2018-07-17 00:32:37</CreatedAt>
                    <AddressUpdatedAt>2018-07-17 05:32:37</AddressUpdatedAt>
                    <AddressBilling></AddressBilling>
                    <AddressShipping></AddressShipping>
                    <NationalRegistrationNumber>72201776</NationalRegistrationNumber>
                    <ItemsCount>1</ItemsCount>
                    <PromisedShippingTime>2018-07-18 23:59:59</PromisedShippingTime>
                    <ExtraAttributes>{"color":"someJsonData", "condition":"someJsonData2"}</ExtraAttributes>
                    <Statuses></Statuses>
               </Order>'
        );

        OrderFactory::make($simpleXml);
    }

    public function testItThrowsAExceptionWithoutAAddressUpdatedAtInTheXml(): void
    {
        $this->expectException(InvalidXmlStructureException::class);

        $this->expectExceptionMessage('The xml structure is not valid for a Order. The property AddressUpdatedAt should exist.');

        $simpleXml = simplexml_load_string(
            '<Order>
                    <OrderId>4632913</OrderId>
                    <CustomerFirstName>first_name+4632913</CustomerFirstName>
                    <CustomerLastName>last_name</CustomerLastName>
                    <OrderNumber>204527353</OrderNumber>
                    <PaymentMethod>CashOnDelivery_Payment</PaymentMethod>
                    <Remarks>someRemark</Remarks>
                    <DeliveryInfo>someDeliveryInfo</DeliveryInfo>
                    <Price>95800.00</Price>
                    <GiftOption>1</GiftOption>
                    <GiftMessage>someGiftMessage</GiftMessage>
                    <VoucherCode>someCode</VoucherCode>
                    <CreatedAt>2018-07-17 00:32:37</CreatedAt>
                    <UpdatedAt>2018-07-19 09:12:11</UpdatedAt>
                    <AddressBilling></AddressBilling>
                    <AddressShipping></AddressShipping>
                    <NationalRegistrationNumber>72201776</NationalRegistrationNumber>
                    <ItemsCount>1</ItemsCount>
                    <PromisedShippingTime>2018-07-18 23:59:59</PromisedShippingTime>
                    <ExtraAttributes>{"color":"someJsonData", "condition":"someJsonData2"}</ExtraAttributes>
                    <Statuses></Statuses>
               </Order>'
        );

        OrderFactory::make($simpleXml);
    }

    public function testItThrowsAExceptionWithoutAAddressBillingInTheXml(): void
    {
        $this->expectException(InvalidXmlStructureException::class);

        $this->expectExceptionMessage('The xml structure is not valid for a Order. The property AddressBilling should exist.');

        $simpleXml = simplexml_load_string(
            '<Order>
                    <OrderId>4632913</OrderId>
                    <CustomerFirstName>first_name+4632913</CustomerFirstName>
                    <CustomerLastName>last_name</CustomerLastName>
                    <OrderNumber>204527353</OrderNumber>
                    <PaymentMethod>CashOnDelivery_Payment</PaymentMethod>
                    <Remarks>someRemark</Remarks>
                    <DeliveryInfo>someDeliveryInfo</DeliveryInfo>
                    <Price>95800.00</Price>
                    <GiftOption>1</GiftOption>
                    <GiftMessage>someGiftMessage</GiftMessage>
                    <VoucherCode>someCode</VoucherCode>
                    <CreatedAt>2018-07-17 00:32:37</CreatedAt>
                    <UpdatedAt>2018-07-19 09:12:11</UpdatedAt>
                    <AddressUpdatedAt>2018-07-17 05:32:37</AddressUpdatedAt>
                    <AddressShipping></AddressShipping>
                    <NationalRegistrationNumber>72201776</NationalRegistrationNumber>
                    <ItemsCount>1</ItemsCount>
                    <PromisedShippingTime>2018-07-18 23:59:59</PromisedShippingTime>
                    <ExtraAttributes>{"color":"someJsonData", "condition":"someJsonData2"}</ExtraAttributes>
                    <Statuses></Statuses>
               </Order>'
        );

        OrderFactory::make($simpleXml);
    }

    public function testItThrowsAExceptionWithoutAAddressShippingInTheXml(): void
    {
        $this->expectException(InvalidXmlStructureException::class);

        $this->expectExceptionMessage('The xml structure is not valid for a Order. The property AddressShipping should exist.');

        $simpleXml = simplexml_load_string(
            '<Order>
                    <OrderId>4632913</OrderId>
                    <CustomerFirstName>first_name+4632913</CustomerFirstName>
                    <CustomerLastName>last_name</CustomerLastName>
                    <OrderNumber>204527353</OrderNumber>
                    <PaymentMethod>CashOnDelivery_Payment</PaymentMethod>
                    <Remarks>someRemark</Remarks>
                    <DeliveryInfo>someDeliveryInfo</DeliveryInfo>
                    <Price>95800.00</Price>
                    <GiftOption>1</GiftOption>
                    <GiftMessage>someGiftMessage</GiftMessage>
                    <VoucherCode>someCode</VoucherCode>
                    <CreatedAt>2018-07-17 00:32:37</CreatedAt>
                    <UpdatedAt>2018-07-19 09:12:11</UpdatedAt>
                    <AddressUpdatedAt>2018-07-17 05:32:37</AddressUpdatedAt>
                    <AddressBilling></AddressBilling>
                    <NationalRegistrationNumber>72201776</NationalRegistrationNumber>
                    <ItemsCount>1</ItemsCount>
                    <PromisedShippingTime>2018-07-18 23:59:59</PromisedShippingTime>
                    <ExtraAttributes>{"color":"someJsonData", "condition":"someJsonData2"}</ExtraAttributes>
                    <Statuses></Statuses>
               </Order>'
        );

        OrderFactory::make($simpleXml);
    }

    public function testItThrowsAExceptionWithoutANationalRegistrationNumberInTheXml(): void
    {
        $this->expectException(InvalidXmlStructureException::class);

        $this->expectExceptionMessage('The xml structure is not valid for a Order. The property NationalRegistrationNumber should exist.');

        $simpleXml = simplexml_load_string(
            '<Order>
                    <OrderId>4632913</OrderId>
                    <CustomerFirstName>first_name+4632913</CustomerFirstName>
                    <CustomerLastName>last_name</CustomerLastName>
                    <OrderNumber>204527353</OrderNumber>
                    <PaymentMethod>CashOnDelivery_Payment</PaymentMethod>
                    <Remarks>someRemark</Remarks>
                    <DeliveryInfo>someDeliveryInfo</DeliveryInfo>
                    <Price>95800.00</Price>
                    <GiftOption>1</GiftOption>
                    <GiftMessage>someGiftMessage</GiftMessage>
                    <VoucherCode>someCode</VoucherCode>
                    <CreatedAt>2018-07-17 00:32:37</CreatedAt>
                    <UpdatedAt>2018-07-19 09:12:11</UpdatedAt>
                    <AddressUpdatedAt>2018-07-17 05:32:37</AddressUpdatedAt>
                    <AddressBilling></AddressBilling>
                    <AddressShipping></AddressShipping>
                    <ItemsCount>1</ItemsCount>
                    <PromisedShippingTime>2018-07-18 23:59:59</PromisedShippingTime>
                    <ExtraAttributes>{"color":"someJsonData", "condition":"someJsonData2"}</ExtraAttributes>
                    <Statuses></Statuses>
               </Order>'
        );

        OrderFactory::make($simpleXml);
    }

    public function testItThrowsAExceptionWithoutAItemsCountInTheXml(): void
    {
        $this->expectException(InvalidXmlStructureException::class);

        $this->expectExceptionMessage('The xml structure is not valid for a Order. The property ItemsCount should exist.');

        $simpleXml = simplexml_load_string(
            '<Order>
                    <OrderId>4632913</OrderId>
                    <CustomerFirstName>first_name+4632913</CustomerFirstName>
                    <CustomerLastName>last_name</CustomerLastName>
                    <OrderNumber>204527353</OrderNumber>
                    <PaymentMethod>CashOnDelivery_Payment</PaymentMethod>
                    <Remarks>someRemark</Remarks>
                    <DeliveryInfo>someDeliveryInfo</DeliveryInfo>
                    <Price>95800.00</Price>
                    <GiftOption>1</GiftOption>
                    <GiftMessage>someGiftMessage</GiftMessage>
                    <VoucherCode>someCode</VoucherCode>
                    <CreatedAt>2018-07-17 00:32:37</CreatedAt>
                    <UpdatedAt>2018-07-19 09:12:11</UpdatedAt>
                    <AddressUpdatedAt>2018-07-17 05:32:37</AddressUpdatedAt>
                    <AddressBilling></AddressBilling>
                    <AddressShipping></AddressShipping>
                    <NationalRegistrationNumber>72201776</NationalRegistrationNumber>
                    <PromisedShippingTime>2018-07-18 23:59:59</PromisedShippingTime>
                    <ExtraAttributes>{"color":"someJsonData", "condition":"someJsonData2"}</ExtraAttributes>
                    <Statuses></Statuses>
               </Order>'
        );

        OrderFactory::make($simpleXml);
    }

    public function testItThrowsAExceptionWithoutAPromisedShippingTimeInTheXml(): void
    {
        $this->expectException(InvalidXmlStructureException::class);

        $this->expectExceptionMessage('The xml structure is not valid for a Order. The property PromisedShippingTime should exist.');

        $simpleXml = simplexml_load_string(
            '<Order>
                    <OrderId>4632913</OrderId>
                    <CustomerFirstName>first_name+4632913</CustomerFirstName>
                    <CustomerLastName>last_name</CustomerLastName>
                    <OrderNumber>204527353</OrderNumber>
                    <PaymentMethod>CashOnDelivery_Payment</PaymentMethod>
                    <Remarks>someRemark</Remarks>
                    <DeliveryInfo>someDeliveryInfo</DeliveryInfo>
                    <Price>95800.00</Price>
                    <GiftOption>1</GiftOption>
                    <GiftMessage>someGiftMessage</GiftMessage>
                    <VoucherCode>someCode</VoucherCode>
                    <CreatedAt>2018-07-17 00:32:37</CreatedAt>
                    <UpdatedAt>2018-07-19 09:12:11</UpdatedAt>
                    <AddressUpdatedAt>2018-07-17 05:32:37</AddressUpdatedAt>
                    <AddressBilling></AddressBilling>
                    <AddressShipping></AddressShipping>
                    <NationalRegistrationNumber>72201776</NationalRegistrationNumber>
                    <ItemsCount>1</ItemsCount>
                    <ExtraAttributes>{"color":"someJsonData", "condition":"someJsonData2"}</ExtraAttributes>
                    <Statuses></Statuses>
               </Order>'
        );

        OrderFactory::make($simpleXml);
    }

    public function testItThrowsAExceptionWithoutAExtraAttributesInTheXml(): void
    {
        $this->expectException(InvalidXmlStructureException::class);

        $this->expectExceptionMessage('The xml structure is not valid for a Order. The property ExtraAttributes should exist.');

        $simpleXml = simplexml_load_string(
            '<Order>
                    <OrderId>4632913</OrderId>
                    <CustomerFirstName>first_name+4632913</CustomerFirstName>
                    <CustomerLastName>last_name</CustomerLastName>
                    <OrderNumber>204527353</OrderNumber>
                    <PaymentMethod>CashOnDelivery_Payment</PaymentMethod>
                    <Remarks>someRemark</Remarks>
                    <DeliveryInfo>someDeliveryInfo</DeliveryInfo>
                    <Price>95800.00</Price>
                    <GiftOption>1</GiftOption>
                    <GiftMessage>someGiftMessage</GiftMessage>
                    <VoucherCode>someCode</VoucherCode>
                    <CreatedAt>2018-07-17 00:32:37</CreatedAt>
                    <UpdatedAt>2018-07-19 09:12:11</UpdatedAt>
                    <AddressUpdatedAt>2018-07-17 05:32:37</AddressUpdatedAt>
                    <AddressBilling></AddressBilling>
                    <AddressShipping></AddressShipping>
                    <NationalRegistrationNumber>72201776</NationalRegistrationNumber>
                    <ItemsCount>1</ItemsCount>
                    <PromisedShippingTime>2018-07-18 23:59:59</PromisedShippingTime>
                    <Statuses></Statuses>
               </Order>'
        );

        OrderFactory::make($simpleXml);
    }

    public function testItThrowsAExceptionWithoutAStatusesInTheXml(): void
    {
        $this->expectException(InvalidXmlStructureException::class);

        $this->expectExceptionMessage('The xml structure is not valid for a Order. The property Statuses should exist.');

        $simpleXml = simplexml_load_string(
            '<Order>
                    <OrderId>4632913</OrderId>
                    <CustomerFirstName>first_name+4632913</CustomerFirstName>
                    <CustomerLastName>last_name</CustomerLastName>
                    <OrderNumber>204527353</OrderNumber>
                    <PaymentMethod>CashOnDelivery_Payment</PaymentMethod>
                    <Remarks>someRemark</Remarks>
                    <DeliveryInfo>someDeliveryInfo</DeliveryInfo>
                    <Price>95800.00</Price>
                    <GiftOption>1</GiftOption>
                    <GiftMessage>someGiftMessage</GiftMessage>
                    <VoucherCode>someCode</VoucherCode>
                    <CreatedAt>2018-07-17 00:32:37</CreatedAt>
                    <UpdatedAt>2018-07-19 09:12:11</UpdatedAt>
                    <AddressUpdatedAt>2018-07-17 05:32:37</AddressUpdatedAt>
                    <AddressBilling></AddressBilling>
                    <AddressShipping></AddressShipping>
                    <NationalRegistrationNumber>72201776</NationalRegistrationNumber>
                    <ItemsCount>1</ItemsCount>
                    <PromisedShippingTime>2018-07-18 23:59:59</PromisedShippingTime>
                    <ExtraAttributes>{"color":"someJsonData", "condition":"someJsonData2"}</ExtraAttributes>
               </Order>'
        );

        OrderFactory::make($simpleXml);
    }

    public function testItReturnsAJsonRepresentation(): void
    {
        $orderId = '4632913';
        $customerFirstName = 'first_name+4632913';
        $customerLastName = 'last_name';
        $orderNumber = '204527353';
        $paymentMethod = 'CashOnDelivery_Payment';
        $remarks = 'someRemark';
        $deliveryInfo = 'someDeliveryInfo';
        $price = '95800.00';
        $giftOption = false;
        $createdAt = '2018-07-17 00:32:37';
        $updatedAt = '2018-07-19 09:12:11';
        $addressUpdatedAt = '2018-07-17 05:32:37';

        $firstName = 'firstName';
        $lastName = 'Rocket';
        $address1 = 'address';
        $city = 'city';
        $postCode = '10117';
        $country = 'country';

        $nationalRegistrationNumber = '72201776';
        $itemsCount = 1;
        $promisedShippingTime = '2018-07-18 23:59:59';
        $statuses = ['pending', 'canceled'];
        $extraAttributes = 'Extra attributes';

        $xml = sprintf(
            '
            <Order>
                <OrderId>%s</OrderId>
                <CustomerFirstName>%s</CustomerFirstName>
                <CustomerLastName>%s</CustomerLastName>
                <OrderNumber>%s</OrderNumber>
                <PaymentMethod>%s</PaymentMethod>
                <Remarks>%s</Remarks>
                <DeliveryInfo>%s</DeliveryInfo>
                <Price>%s</Price>
                <GiftOption>%s</GiftOption>
                <GiftMessage/>
                <VoucherCode/>
                <CreatedAt>%s</CreatedAt>
                <UpdatedAt>%s</UpdatedAt>
                <AddressUpdatedAt>%s</AddressUpdatedAt>
                <AddressBilling>
                     <FirstName>%s</FirstName>
                     <LastName>%s</LastName>
                     <Phone/>
                     <Phone2/>
                     <Address1>%s</Address1>
                     <CustomerEmail/>
                     <City>%s</City>
                     <Ward/>
                     <Region/>
                     <PostCode>%s</PostCode>
                     <Country>%s</Country>
                </AddressBilling>
                <AddressShipping>
                     <FirstName>%s</FirstName>
                     <LastName>%s</LastName>
                     <Phone/>
                     <Phone2/>
                     <Address1>%s</Address1>
                     <CustomerEmail/>
                     <City>%s</City>
                     <Ward/>
                     <Region/>
                     <PostCode>%s</PostCode>
                     <Country>%s</Country>
                </AddressShipping>
                <NationalRegistrationNumber>%s</NationalRegistrationNumber>
                <ItemsCount>%s</ItemsCount>
                <PromisedShippingTime>%s</PromisedShippingTime>
                <ExtraAttributes>%s</ExtraAttributes>
                <Statuses>
                     <Status>%s</Status>
                     <Status>%s</Status>
                </Statuses>
           </Order>',
            $orderId,
            $customerFirstName,
            $customerLastName,
            $orderNumber,
            $paymentMethod,
            $remarks,
            $deliveryInfo,
            $price,
            $giftOption,
            $createdAt,
            $updatedAt,
            $addressUpdatedAt,
            $firstName,
            $lastName,
            $address1,
            $city,
            $postCode,
            $country,
            $firstName,
            $lastName,
            $address1,
            $city,
            $postCode,
            $country,
            $nationalRegistrationNumber,
            $itemsCount,
            $promisedShippingTime,
            $extraAttributes,
            $statuses[0],
            $statuses[1]
        );

        $simpleXml = simplexml_load_string($xml);

        $order = OrderFactory::make($simpleXml);

        $expectedJson = sprintf(
            '{"orderId":%d,"customerFirstName":"%s","customerLastName":"%s","orderNumber":%d,"paymentMethod":"%s","remarks":"%s","deliveryInfo":"%s","price":%d,"giftOption":%s,"giftMessage":"","voucherCode":"","createdAt":%s,"updatedAt":%s,"addressUpdatedAt":%s, "addressBilling": {"firstName":"%s","lastName":"%s","phone":null,"phone2":null,"address":"%s","customerEmail":null,"city":"%s","ward":null,"region":null,"postCode":"%s","country":"%s"}, "addressShipping": {"firstName":"%s","lastName":"%s","phone":null,"phone2":null,"address":"%s","customerEmail":null,"city":"%s","ward":null,"region":null,"postCode":"%s","country":"%s"},"nationalRegistrationNumber": "%s","itemsCount": %d,"promisedShippingTime": %s,"extraAttributes": "%s","statuses": ["%s","%s"],"orderItems": null}',
            $orderId,
            $customerFirstName,
            $customerLastName,
            $orderNumber,
            $paymentMethod,
            $remarks,
            $deliveryInfo,
            $price,
            $giftOption ? 'true' : 'false',
            Json::encode($order->getCreatedAt()),
            Json::encode($order->getUpdatedAt()),
            Json::encode($order->getAddressUpdatedAt()),
            $firstName,
            $lastName,
            $address1,
            $city,
            $postCode,
            $country,
            $firstName,
            $lastName,
            $address1,
            $city,
            $postCode,
            $country,
            $nationalRegistrationNumber,
            $itemsCount,
            Json::encode($order->getPromisedShippingTime()),
            $extraAttributes,
            $statuses[0],
            $statuses[1]
        );

        $this->assertJsonStringEqualsJsonString($expectedJson, Json::encode($order));
    }

    public function testItReturnsAJsonRepresentationWithOrderItems(): void
    {
        $orderId = 1;
        $orderNumber = 1;
        $randomDigit = $this->getFaker()->randomDigitNotNull;

        $orderItems = new OrderItems();
        $orderItem = OrderItem::fromStatus($randomDigit, $randomDigit, (string) $randomDigit, (string) $randomDigit);
        $orderItems->add($orderItem);

        $order = Order::fromItems($orderId, $orderNumber, $orderItems);
        $json = Json::encode($order);

        $expectedJson = sprintf(
            '{"orderId":%d,"customerFirstName":null,"customerLastName":null,"orderNumber":%d,"paymentMethod":null,"remarks":null,"deliveryInfo":null,"price":null,"giftOption":null,"giftMessage":null,"voucherCode":null,"createdAt":null,"updatedAt":null,"addressUpdatedAt":null,"addressBilling":null,"addressShipping":null,"nationalRegistrationNumber":null,"itemsCount":null,"promisedShippingTime":null,"extraAttributes":null,"statuses":null,"orderItems":[{"orderItemId":%d,"shopId":null,"orderId":null,"name":null,"sku":null,"variation":null,"shopSku":null,"shippingType":null,"itemPrice":null,"paidPrice":null,"currency":null,"walletCredits":null,"taxAmount":null,"codCollectableAmount":null,"shippingAmount":null,"shippingServiceCost":null,"voucherAmount":null,"voucherCode":null,"status":null,"isProcessable":null,"shipmentProvider":null,"isDigital":null,"digitalDeliveryInfo":null,"trackingCode":null,"trackingCodePre":null,"reason":null,"reasonDetail":null,"purchaseOrderId":%d,"purchaseOrderNumber":"%s","packageId":"%s","promisedShippingTime":null,"extraAttributes":null,"shippingProviderType":null,"createdAt":null,"updatedAt":null,"returnStatus":null}]}',
            $orderId,
            $orderNumber,
            $randomDigit,
            $randomDigit,
            $randomDigit,
            $randomDigit
        );

        $this->assertJsonStringEqualsJsonString($expectedJson, Json::encode($order));
    }

    private function getValidOrderXmlStructure()
    {
        return '<Order>
                    <OrderId>4632913</OrderId>
                    <CustomerFirstName>first_name+4632913</CustomerFirstName>
                    <CustomerLastName>last_name</CustomerLastName>
                    <OrderNumber>204527353</OrderNumber>
                    <PaymentMethod>CashOnDelivery_Payment</PaymentMethod>
                    <Remarks>someRemark</Remarks>
                    <DeliveryInfo>someDeliveryInfo</DeliveryInfo>
                    <Price>95800.00</Price>
                    <GiftOption>1</GiftOption>
                    <GiftMessage>someGiftMessage</GiftMessage>
                    <VoucherCode>someCode</VoucherCode>
                    <CreatedAt>2018-07-17 00:32:37</CreatedAt>
                    <UpdatedAt>2018-07-19 09:12:11</UpdatedAt>
                    <AddressUpdatedAt>2018-07-17 05:32:37</AddressUpdatedAt>
                    <AddressBilling>
                         <FirstName>Labs</FirstName>
                         <LastName>Rocket</LastName>
                         <Phone/>
                         <Phone2/>
                         <Address1>Johannisstr. 20</Address1>
                         <CustomerEmail/>
                         <City>Berlin</City>
                         <Ward/>
                         <Region/>
                         <PostCode>10117</PostCode>
                         <Country>Germany</Country>
                    </AddressBilling>
                    <AddressShipping>
                         <FirstName>Rocket</FirstName>
                         <LastName>Labs</LastName>
                         <Phone/>
                         <Phone2/>
                         <Address1>Charlottenstrae 4</Address1>
                         <CustomerEmail/>
                         <City>Berlin</City>
                         <Ward/>
                         <Region/>
                         <PostCode>10969</PostCode>
                         <Country>Germany</Country>
                    </AddressShipping>
                    <NationalRegistrationNumber>72201776</NationalRegistrationNumber>
                    <ItemsCount>1</ItemsCount>
                    <PromisedShippingTime>2018-07-18 23:59:59</PromisedShippingTime>
                    <ExtraAttributes>Extra atrubutte</ExtraAttributes>
                    <Statuses>
                         <Status>delivered</Status>
                         <Status>pending</Status>
                    </Statuses>
               </Order>';
    }
}

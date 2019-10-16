<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Order;

use Linio\SellerCenter\Exception\InvalidXmlStructureException;
use Linio\SellerCenter\Factory\Xml\Order\OrdersFactory;
use Linio\SellerCenter\Factory\Xml\Order\OrdersItemsFactory;
use Linio\SellerCenter\LinioTestCase;
use Linio\SellerCenter\Model\Order\Order;
use Linio\SellerCenter\Model\Order\OrderItem;
use Linio\SellerCenter\Model\Order\OrderItems;
use Linio\SellerCenter\Model\Order\Orders;
use SimpleXMLElement;

class OrdersTest extends LinioTestCase
{
    public function testItReturnsACollectionOfOrders(): void
    {
        $simpleXml = new SimpleXMLElement($this->getOrderResponse());

        $orders = OrdersFactory::make($simpleXml);

        $orderList = $orders->all();

        $order = $orders->findByOrderId(4687808);

        $this->assertInstanceOf(Orders::class, $orders);
        $this->assertInstanceOf(Order::class, $order);
        $this->assertContainsOnlyInstancesOf(Order::class, $orderList);

        foreach ($orderList as $order) {
            $this->assertInstanceOf(Order::class, $order);
            $this->assertNull($order->getOrderItems());
        }
    }

    public function testItReturnsACollectionOfOrderItems(): void
    {
        $simpleXml = new SimpleXMLElement($this->getOrderItemResponse());

        $orders = OrdersItemsFactory::make($simpleXml);

        $orderList = $orders->all();

        $order = $orders->findByOrderId(4687808);

        $this->assertInstanceOf(Orders::class, $orders);
        $this->assertInstanceOf(Order::class, $order);
        $this->assertContainsOnlyInstancesOf(Order::class, $orderList);

        foreach ($orderList as $order) {
            $this->assertInstanceOf(Order::class, $order);
            $this->assertInstanceOf(OrderItems::class, $order->getOrderItems());
            $this->assertContainsOnlyInstancesOf(OrderItem::class, $order->getOrderItems()->all());
        }
    }

    public function testItReturnNullWithAInvalidOrderId(): void
    {
        $simpleXml = new SimpleXMLElement($this->getOrderResponse());

        $orders = OrdersFactory::make($simpleXml);

        $order = $orders->findByOrderId(12);

        $this->assertNull($order);
    }

    public function testItThrowsAExceptionWithoutAOrderIdInTheXml(): void
    {
        $this->expectException(InvalidXmlStructureException::class);

        $this->expectExceptionMessage('The xml structure is not valid for a Order. The property OrderId should exist.');

        $simpleXml = simplexml_load_string(
            '<Body>
                      <Orders>
                           <Order>
                                <OrderNumber>206125233</OrderNumber>
                                <OrderItems></OrderItems>
                            </Order>
                        </Orders>
                    </Body>'
        );

        OrdersItemsFactory::make($simpleXml);
    }

    public function testItThrowsAExceptionWithoutAOrderNumberInTheXml(): void
    {
        $this->expectException(InvalidXmlStructureException::class);

        $this->expectExceptionMessage('The xml structure is not valid for a Order. The property OrderNumber should exist.');

        $simpleXml = simplexml_load_string(
            '<Body>
                      <Orders>
                           <Order>
                                <OrderId>4687503</OrderId>
                                <OrderItems></OrderItems>
                            </Order>
                        </Orders>
                    </Body>'
        );

        OrdersItemsFactory::make($simpleXml);
    }

    public function testItThrowsAExceptionWithoutAOrderItemsInTheXml(): void
    {
        $this->expectException(InvalidXmlStructureException::class);

        $this->expectExceptionMessage('The xml structure is not valid for a Order. The property OrderItems should exist.');

        $simpleXml = simplexml_load_string(
            '<Body>
                      <Orders>
                           <Order>
                                <OrderId>4687503</OrderId>
                                <OrderNumber>206125233</OrderNumber>
                            </Order>
                        </Orders>
                    </Body>'
        );

        OrdersItemsFactory::make($simpleXml);
    }

    public function getOrderResponse(): string
    {
        return '<Body>
                  <Orders>
                       <Order>
                            <OrderId>4687503</OrderId>
                            <CustomerFirstName>first_name+4687503</CustomerFirstName>
                            <CustomerLastName>last_name+</CustomerLastName>
                            <OrderNumber>206125233</OrderNumber>
                            <PaymentMethod>CashOnDelivery_Payment</PaymentMethod>
                            <Remarks/>
                            <DeliveryInfo>SE PAGA CON TARGETA DEBITO</DeliveryInfo>
                            <Price>61800.00</Price>
                            <GiftOption>0</GiftOption>
                            <GiftMessage/>
                            <VoucherCode/>
                            <CreatedAt>2018-08-27 08:48:20</CreatedAt>
                            <UpdatedAt>2018-08-28 17:09:47</UpdatedAt>
                            <AddressUpdatedAt>2018-08-27 13:48:20</AddressUpdatedAt>
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
                                 <Address1>Charlottenstraße 4</Address1>
                                 <CustomerEmail/>
                                 <City>Berlin</City>
                                 <Ward/>
                                 <Region/>
                                 <PostCode>10969</PostCode>
                                 <Country>Germany</Country>
                            </AddressShipping>
                            <NationalRegistrationNumber>94526898</NationalRegistrationNumber>
                            <ItemsCount>1</ItemsCount>
                            <PromisedShippingTime>2018-08-28 16:00:00</PromisedShippingTime>
                            <ExtraAttributes/>
                            <Statuses>
                                 <Status>delivered</Status>
                            </Statuses>
                       </Order>
                       <Order>
                            <OrderId>4687808</OrderId>
                            <CustomerFirstName>first_name+4687808</CustomerFirstName>
                            <CustomerLastName>last_name+</CustomerLastName>
                            <OrderNumber>800327689</OrderNumber>
                            <PaymentMethod>Avianca_Miles_Only_Payment</PaymentMethod>
                            <Remarks/>
                            <DeliveryInfo/>
                            <Price>125806.85</Price>
                            <GiftOption>0</GiftOption>
                            <GiftMessage/>
                            <VoucherCode/>
                            <CreatedAt>2018-08-27 11:40:23</CreatedAt>
                            <UpdatedAt>2018-08-28 16:09:03</UpdatedAt>
                            <AddressUpdatedAt>2018-08-27 16:40:23</AddressUpdatedAt>
                            <AddressBilling>
                                 <FirstName>Labs</FirstName>
                                 <LastName>Rocket</LastName>
                                 <Phone/>
                                 <Phone2/>
                                 <Address1>Johannisstr. 20</Address1>
                                 <Address2/>
                                 <Address3/>
                                 <Address4/>
                                 <Address5/>
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
                                 <Address1>Charlottenstraße 4</Address1>
                                 <Address2/>
                                 <Address3/>
                                 <Address4/>
                                 <Address5/>
                                 <CustomerEmail/>
                                 <City>Berlin</City>
                                 <Ward/>
                                 <Region/>
                                 <PostCode>10969</PostCode>
                                 <Country>Germany</Country>
                            </AddressShipping>
                            <NationalRegistrationNumber>76043323</NationalRegistrationNumber>
                            <ItemsCount>1</ItemsCount>
                            <PromisedShippingTime>2018-08-28 16:00:00</PromisedShippingTime>
                            <ExtraAttributes/>
                            <Statuses>
                                 <Status>delivered</Status>
                            </Statuses>
                       </Order>
                  </Orders>
                </Body>';
    }

    public function getOrderItemResponse(): string
    {
        return '<Body>
                  <Orders>
                       <Order>
                            <OrderId>4687503</OrderId>
                            <OrderNumber>206125233</OrderNumber>
                            <OrderItems>
                                 <OrderItem>
                                      <OrderItemId>6653173</OrderItemId>
                                      <ShopId>7068646</ShopId>
                                      <OrderId>4687503</OrderId>
                                      <Name>RELOJ WEIDE DE ACERO INOXIDABLE DE ALTA CALIDAD 1009 SILVER BLACK</Name>
                                      <Sku>WE817FA21TYC3LCO</Sku>
                                      <Variation>Talla Única</Variation>
                                      <ShopSku>WE895FA0Z828WLCO-2510173</ShopSku>
                                      <ShippingType>Dropshipping</ShippingType>
                                      <ItemPrice>55900.00</ItemPrice>
                                      <PaidPrice>55900.00</PaidPrice>
                                      <Currency>COP</Currency>
                                      <WalletCredits>0.00</WalletCredits>
                                      <TaxAmount>0.00</TaxAmount>
                                      <CodCollectableAmount/>
                                      <ShippingAmount>5900.00</ShippingAmount>
                                      <ShippingServiceCost>4442.02</ShippingServiceCost>
                                      <VoucherAmount>0</VoucherAmount>
                                      <VoucherCode/>
                                      <Status>delivered</Status>
                                      <IsProcessable>1</IsProcessable>
                                      <ShipmentProvider>TCC</ShipmentProvider>
                                      <IsDigital>0</IsDigital>
                                      <DigitalDeliveryInfo/>
                                      <TrackingCode>427573467</TrackingCode>
                                      <TrackingCodePre/>
                                      <Reason/>
                                      <ReasonDetail/>
                                      <PurchaseOrderId>0</PurchaseOrderId>
                                      <PurchaseOrderNumber/>
                                      <PackageId>1000405653600</PackageId>
                                      <PromisedShippingTime>2018-08-28 16:00:00</PromisedShippingTime>
                                      <ExtraAttributes/>
                                      <ShippingProviderType>standard</ShippingProviderType>
                                      <CreatedAt>2018-08-27 08:48:20</CreatedAt>
                                      <UpdatedAt>2018-08-28 17:09:47</UpdatedAt>
                                      <ReturnStatus/>
                                 </OrderItem>
                            </OrderItems>
                       </Order>
                       <Order>
                            <OrderId>4687808</OrderId>
                            <OrderNumber>800327689</OrderNumber>
                            <OrderItems>
                                 <OrderItem>
                                      <OrderItemId>6653602</OrderItemId>
                                      <ShopId>7069240</ShopId>
                                      <OrderId>4687808</OrderId>
                                      <Name>Reloj Megir ML 2020 Impermeable Japones De Cuarzo Negro</Name>
                                      <Sku>EP162FA80HRBLCO</Sku>
                                      <Variation>Talla Única</Variation>
                                      <ShopSku>ME803FA03DRNYLCO-3219643</ShopSku>
                                      <ShippingType>Dropshipping</ShippingType>
                                      <ItemPrice>119903.23</ItemPrice>
                                      <PaidPrice>119903.23</PaidPrice>
                                      <Currency>COP</Currency>
                                      <WalletCredits>0.00</WalletCredits>
                                      <TaxAmount>0.00</TaxAmount>
                                      <CodCollectableAmount/>
                                      <ShippingAmount>5903.62</ShippingAmount>
                                      <ShippingServiceCost>4020.27</ShippingServiceCost>
                                      <VoucherAmount>0</VoucherAmount>
                                      <VoucherCode/>
                                      <Status>delivered</Status>
                                      <IsProcessable>1</IsProcessable>
                                      <ShipmentProvider>Deprisa</ShipmentProvider>
                                      <IsDigital>0</IsDigital>
                                      <DigitalDeliveryInfo/>
                                      <TrackingCode>999046151929</TrackingCode>
                                      <TrackingCodePre/>
                                      <Reason/>
                                      <ReasonDetail/>
                                      <PurchaseOrderId>0</PurchaseOrderId>
                                      <PurchaseOrderNumber/>
                                      <PackageId>1000405682300</PackageId>
                                      <PromisedShippingTime>2018-08-28 16:00:00</PromisedShippingTime>
                                      <ExtraAttributes/>
                                      <ShippingProviderType>standard</ShippingProviderType>
                                      <CreatedAt>2018-08-27 11:40:23</CreatedAt>
                                      <UpdatedAt>2018-08-28 16:09:03</UpdatedAt>
                                      <ReturnStatus/>
                                 </OrderItem>
                            </OrderItems>
                       </Order>
                  </Orders>
             </Body>';
    }
}

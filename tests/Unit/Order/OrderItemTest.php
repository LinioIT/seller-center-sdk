<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Order;

use DateTimeImmutable;
use Linio\Component\Util\Json;
use Linio\SellerCenter\Exception\InvalidXmlStructureException;
use Linio\SellerCenter\Factory\Xml\Order\OrderItemFactory;
use Linio\SellerCenter\LinioTestCase;
use Linio\SellerCenter\Model\Order\OrderItem;
use SimpleXMLElement;

class OrderItemTest extends LinioTestCase
{
    public function testItReturnsTheValueWithEachAccessor(): void
    {
        $simpleXml = simplexml_load_string(sprintf('<OrderItem>
                    <OrderItemId>6750999</OrderItemId>
                    <ShopId>7208215</ShopId>
                    <OrderId>4758978</OrderId>
                    <Name>MEGIR 5006 RELOJ ACERO INOXIDABLE ROSA</Name>
                    <Sku>DJFKLJOEDKLFJ</Sku>
                    <Variation>Talla Única</Variation>
                    <ShopSku>ME803FA0UEI9YLCO-6073653</ShopSku>
                    <ShippingType>Dropshipping</ShippingType>
                    <ItemPrice>89900.00</ItemPrice>
                    <PaidPrice>89900.00</PaidPrice>
                    <Currency>COP</Currency>
                    <WalletCredits>0.00</WalletCredits>
                    <TaxAmount>0.00</TaxAmount>
                    <CodCollectableAmount>schasmakcmaosmco</CodCollectableAmount>
                    <ShippingAmount>0.00</ShippingAmount>
                    <ShippingServiceCost>7000.00</ShippingServiceCost>
                    <VoucherAmount>0</VoucherAmount>
                    <VoucherCode>msxnwinsiqni</VoucherCode>
                    <Status>pending</Status>
                    <IsProcessable>1</IsProcessable>
                    <ShipmentProvider>LOGISTICA</ShipmentProvider>
                    <IsDigital>0</IsDigital>
                    <DigitalDeliveryInfo>OK</DigitalDeliveryInfo>
                    <TrackingCode>1000414030800</TrackingCode>
                    <TrackingCodePre>1000414030800</TrackingCodePre>
                    <Reason>OK</Reason>
                    <ReasonDetail>OK</ReasonDetail>
                    <PurchaseOrderId>0</PurchaseOrderId>
                    <PurchaseOrderNumber>23781748362</PurchaseOrderNumber>
                    <PackageId>1000414030800</PackageId>
                    <PromisedShippingTime>2018-10-16 20:00:00</PromisedShippingTime>
                    <ExtraAttributes>{"color":"red", "isGift":"true"}</ExtraAttributes>
                    <ShippingProviderType>express</ShippingProviderType>
                    <CreatedAt>2018-10-13 23:08:34</CreatedAt>
                    <UpdatedAt>2018-10-14 13:30:50</UpdatedAt>
                    <ReturnStatus>approved</ReturnStatus>
               </OrderItem>'));

        $orderItem = OrderItemFactory::make($simpleXml);

        $this->assertInstanceOf(OrderItem::class, $orderItem);
        $this->assertEquals($orderItem->getOrderItemId(), (int) $simpleXml->OrderItemId);
        $this->assertEquals($orderItem->getShopId(), (int) $simpleXml->ShopId);
        $this->assertEquals($orderItem->getOrderId(), (int) $simpleXml->OrderId);
        $this->assertEquals($orderItem->getName(), (string) $simpleXml->Name);
        $this->assertEquals($orderItem->getSku(), (string) $simpleXml->Sku);
        $this->assertEquals($orderItem->getVariation(), (string) $simpleXml->Variation);
        $this->assertEquals($orderItem->getShopSku(), (string) $simpleXml->ShopSku);
        $this->assertEquals($orderItem->getShippingType(), (string) $simpleXml->ShippingType);
        $this->assertEquals($orderItem->getItemPrice(), (float) $simpleXml->ItemPrice);
        $this->assertEquals($orderItem->getPaidPrice(), (float) $simpleXml->PaidPrice);
        $this->assertEquals($orderItem->getCurrency(), (string) $simpleXml->Currency);
        $this->assertEquals($orderItem->getWalletCredits(), (float) $simpleXml->WalletCredits);
        $this->assertEquals($orderItem->getTaxAmount(), (float) $simpleXml->TaxAmount);
        $this->assertEquals($orderItem->getCodCollectableAmount(), (float) $simpleXml->CodCollectableAmount);
        $this->assertEquals($orderItem->getShippingAmount(), (float) $simpleXml->ShippingAmount);
        $this->assertEquals($orderItem->getShippingServiceCost(), (float) $simpleXml->ShippingServiceCost);
        $this->assertEquals($orderItem->getVoucherAmount(), (int) $simpleXml->VoucherAmount);
        $this->assertEquals($orderItem->getVoucherCode(), (string) $simpleXml->VoucherCode);
        $this->assertEquals($orderItem->getStatus(), (string) $simpleXml->Status);
        $this->assertEquals($orderItem->getIsProcessable(), (int) $simpleXml->IsProcessable);
        $this->assertEquals($orderItem->getShipmentProvider(), (string) $simpleXml->ShipmentProvider);
        $this->assertEquals($orderItem->getIsDigital(), (int) $simpleXml->IsDigital);
        $this->assertEquals($orderItem->getDigitalDeliveryInfo(), (string) $simpleXml->DigitalDeliveryInfo);
        $this->assertEquals($orderItem->getTrackingCode(), (string) $simpleXml->TrackingCode);
        $this->assertEquals($orderItem->getTrackingCodePre(), (string) $simpleXml->TrackingCodePre);
        $this->assertEquals($orderItem->getReason(), (string) $simpleXml->Reason);
        $this->assertEquals($orderItem->getReasonDetail(), (string) $simpleXml->ReasonDetail);
        $this->assertEquals($orderItem->getPurchaseOrderId(), (int) $simpleXml->PurchaseOrderId);
        $this->assertEquals($orderItem->getPurchaseOrderNumber(), (string) $simpleXml->PurchaseOrderNumber);
        $this->assertEquals($orderItem->getPackageId(), (string) $simpleXml->PackageId);
        $this->assertEquals($orderItem->getPromisedShippingTime(), DateTimeImmutable::createFromFormat('Y-m-d H:i:s', (string) $simpleXml->PromisedShippingTime));
        $this->assertEquals($orderItem->getExtraAttributes(), Json::decode((string) $simpleXml->ExtraAttributes));
        $this->assertEquals($orderItem->getShippingProviderType(), (string) $simpleXml->ShippingProviderType);
        $this->assertEquals($orderItem->getCreatedAt(), DateTimeImmutable::createFromFormat('Y-m-d H:i:s', (string) $simpleXml->CreatedAt));
        $this->assertEquals($orderItem->getUpdatedAt(), DateTimeImmutable::createFromFormat('Y-m-d H:i:s', (string) $simpleXml->UpdatedAt));
        $this->assertEquals($orderItem->getReturnStatus(), (string) $simpleXml->ReturnStatus);
    }

    public function testItReturnsTheNullWithoutAnExtraAttributeTag(): void
    {
        $simpleXml = simplexml_load_string(sprintf('<OrderItem>
                    <OrderItemId>6750999</OrderItemId>
                    <ShopId>7208215</ShopId>
                    <OrderId>4758978</OrderId>
                    <Name>MEGIR 5006 RELOJ ACERO INOXIDABLE ROSA</Name>
                    <Sku>DJFKLJOEDKLFJ</Sku>
                    <Variation>Talla Única</Variation>
                    <ShopSku>ME803FA0UEI9YLCO-6073653</ShopSku>
                    <ShippingType>Dropshipping</ShippingType>
                    <ItemPrice>89900.00</ItemPrice>
                    <PaidPrice>89900.00</PaidPrice>
                    <Currency>COP</Currency>
                    <WalletCredits>0.00</WalletCredits>
                    <TaxAmount>0.00</TaxAmount>
                    <CodCollectableAmount/>
                    <ShippingAmount>0.00</ShippingAmount>
                    <ShippingServiceCost>7000.00</ShippingServiceCost>
                    <VoucherAmount>0</VoucherAmount>
                    <VoucherCode/>
                    <Status>pending</Status>
                    <IsProcessable>1</IsProcessable>
                    <ShipmentProvider>LOGISTICA</ShipmentProvider>
                    <IsDigital>0</IsDigital>
                    <DigitalDeliveryInfo/>
                    <TrackingCode>1000414030800</TrackingCode>
                    <TrackingCodePre/>
                    <Reason/>
                    <ReasonDetail/>
                    <PurchaseOrderId>0</PurchaseOrderId>
                    <PurchaseOrderNumber/>
                    <PackageId>1000414030800</PackageId>
                    <PromisedShippingTime>2018-10-16 20:00:00</PromisedShippingTime>
                    <ShippingProviderType>express</ShippingProviderType>
                    <ExtraAttributes></ExtraAttributes>
                    <CreatedAt>invalid</CreatedAt>
                    <UpdatedAt>invalid</UpdatedAt>
                    <ReturnStatus/>
               </OrderItem>'));

        $orderItem = OrderItemFactory::make($simpleXml);

        $this->assertInstanceOf(OrderItem::class, $orderItem);
        $this->assertNull($orderItem->getCodCollectableAmount());
        $this->assertNull($orderItem->getVoucherCode());
        $this->assertNull($orderItem->getDigitalDeliveryInfo());
        $this->assertNull($orderItem->getTrackingCodePre());
        $this->assertNull($orderItem->getReason());
        $this->assertNull($orderItem->getReasonDetail());
        $this->assertNull($orderItem->getPurchaseOrderNumber());
        $this->assertNull($orderItem->getExtraAttributes());
        $this->assertNull($orderItem->getCreatedAt());
        $this->assertNull($orderItem->getUpdatedAt());
        $this->assertNull($orderItem->getReturnStatus());
    }

    public function testItReturnsAOrderItemFromAnXml(): void
    {
        $simpleXml = simplexml_load_string(
            '<OrderItem>
                    <OrderItemId>1</OrderItemId>
                    <PurchaseOrderId>123456</PurchaseOrderId>
                    <PurchaseOrderNumber>ABC-123456</PurchaseOrderNumber>
                    <PackageId>MPDS-200131783-9800</PackageId>
                  </OrderItem>'
        );

        $orderItem = OrderItemFactory::makeFromStatus($simpleXml);

        $this->assertInstanceOf(OrderItem::class, $orderItem);
        $this->assertEquals((int) $simpleXml->OrderItemId, $orderItem->getOrderItemId());
        $this->assertEquals((int) $simpleXml->PurchaseOrderId, $orderItem->getPurchaseOrderId());
        $this->assertEquals((string) $simpleXml->PurchaseOrderNumber, $orderItem->getPurchaseOrderNumber());
        $this->assertEquals((string) $simpleXml->PackageId, $orderItem->getPackageId());
    }

    public function testItThrowsAExceptionWithoutAPurchaseOrderIdInTheXml(): void
    {
        $this->expectException(InvalidXmlStructureException::class);

        $this->expectExceptionMessage('The xml structure is not valid for a OrderItem. The property PurchaseOrderId should exist.');

        $simpleXml = simplexml_load_string(
            '<OrderItem>
                    <OrderItemId>1</OrderItemId>
                    <PurchaseOrderNumber>ABC-123456</PurchaseOrderNumber>
                    <PackageId>MPDS-200131783-9800</PackageId>
                  </OrderItem>'
        );

        OrderItemFactory::makeFromStatus($simpleXml);
    }

    public function testItThrowsAExceptionWithoutAPurchaseOrderNumberInTheXml(): void
    {
        $this->expectException(InvalidXmlStructureException::class);

        $this->expectExceptionMessage('The xml structure is not valid for a OrderItem. The property PurchaseOrderNumber should exist.');

        $simpleXml = simplexml_load_string(
            '<OrderItem>
                    <OrderItemId>1</OrderItemId>
                    <PurchaseOrderId>123456</PurchaseOrderId>
                    <PackageId>MPDS-200131783-9800</PackageId>
                  </OrderItem>'
        );

        OrderItemFactory::makeFromStatus($simpleXml);
    }

    /**
     * @dataProvider simpleXmlElementsWithoutAParameter
     */
    public function testThrowsAnExceptionWithoutAParameterInTheXml(SimpleXMLElement $simpleXml, string $message): void
    {
        $this->expectException(InvalidXmlStructureException::class);

        $this->expectExceptionMessage($message);

        OrderItemFactory::make($simpleXml);
    }

    public function simpleXmlElementsWithoutAParameter()
    {
        $xml = '<OrderItem>
                    <OrderItemId>6752675</OrderItemId>
                    <ShopId>4596164</ShopId>
                    <OrderId>4760407</OrderId>
                    <Name>RELOJ WEIDE 3401 ACERO INOXIDABLE BLACK RED DISEÑO MILITAR</Name>
                    <Sku>WE880FA21TYC3LCO_DELETED_2019-01-08_11-00-08</Sku>
                    <Variation>Talla Única</Variation>
                    <ShopSku>WE895FA1GBRAKLCO-2457669</ShopSku>
                    <ShippingType>Dropshipping</ShippingType>
                    <ItemPrice>89900.00</ItemPrice>
                    <PaidPrice>89900.00</PaidPrice>
                    <Currency>COP</Currency>
                    <WalletCredits>0.00</WalletCredits>
                    <TaxAmount>14353.80</TaxAmount>
                    <CodCollectableAmount/>
                    <ShippingAmount>4900.00</ShippingAmount>
                    <ShippingServiceCost>2398.70</ShippingServiceCost>
                    <VoucherAmount>0</VoucherAmount>
                    <VoucherCode/>
                    <Status>pending</Status>
                    <IsProcessable>1</IsProcessable>
                    <ShipmentProvider>TCC</ShipmentProvider>
                    <IsDigital>0</IsDigital>
                    <DigitalDeliveryInfo/>
                    <TrackingCode/>
                    <TrackingCodePre/>
                    <Reason/>￼
                    <ReasonDetail/>
                    <PurchaseOrderId>0</PurchaseOrderId>
                    <PurchaseOrderNumber/>
                    <PackageId>1000256620600</PackageId>
                    <PromisedShippingTime>2017-12-20 16:00:00</PromisedShippingTime>
                    <ExtraAttributes/>
                    <ShippingProviderType>standard</ShippingProviderType>
                    <CreatedAt>2018-10-15 10:08:31</CreatedAt>
                    <UpdatedAt>2019-01-17 16:26:03</UpdatedAt>
                    <ReturnStatus/>
               </OrderItem>';

        $xml1 = new SimpleXMLElement($xml);
        unset($xml1->OrderItemId);

        $xml2 = new SimpleXMLElement($xml);
        unset($xml2->ShopId);

        $xml3 = new SimpleXMLElement($xml);
        unset($xml3->OrderId);

        $xml4 = new SimpleXMLElement($xml);
        unset($xml4->Name);

        $xml5 = new SimpleXMLElement($xml);
        unset($xml5->Sku);

        $xml6 = new SimpleXMLElement($xml);
        unset($xml6->Variation);

        $xml7 = new SimpleXMLElement($xml);
        unset($xml7->ShopSku);

        $xml8 = new SimpleXMLElement($xml);
        unset($xml8->ShippingType);

        $xml9 = new SimpleXMLElement($xml);
        unset($xml9->ItemPrice);

        $xml10 = new SimpleXMLElement($xml);
        unset($xml10->PaidPrice);

        $xml11 = new SimpleXMLElement($xml);
        unset($xml11->Currency);

        $xml12 = new SimpleXMLElement($xml);
        unset($xml12->WalletCredits);

        $xml13 = new SimpleXMLElement($xml);
        unset($xml13->TaxAmount);

        $xml14 = new SimpleXMLElement($xml);
        unset($xml14->CodCollectableAmount);

        $xml15 = new SimpleXMLElement($xml);
        unset($xml15->ShippingAmount);

        $xml16 = new SimpleXMLElement($xml);
        unset($xml16->ShippingServiceCost);

        $xml17 = new SimpleXMLElement($xml);
        unset($xml17->VoucherAmount);

        $xml18 = new SimpleXMLElement($xml);
        unset($xml18->VoucherCode);

        $xml19 = new SimpleXMLElement($xml);
        unset($xml19->Status);

        $xml20 = new SimpleXMLElement($xml);
        unset($xml20->IsProcessable);

        $xml21 = new SimpleXMLElement($xml);
        unset($xml21->ShipmentProvider);

        $xml22 = new SimpleXMLElement($xml);
        unset($xml22->IsDigital);

        $xml23 = new SimpleXMLElement($xml);
        unset($xml23->DigitalDeliveryInfo);

        $xml24 = new SimpleXMLElement($xml);
        unset($xml24->TrackingCode);

        $xml25 = new SimpleXMLElement($xml);
        unset($xml25->TrackingCodePre);

        $xml26 = new SimpleXMLElement($xml);
        unset($xml26->Reason);

        $xml27 = new SimpleXMLElement($xml);
        unset($xml27->ReasonDetail);

        $xml28 = new SimpleXMLElement($xml);
        unset($xml28->PurchaseOrderId);

        $xml29 = new SimpleXMLElement($xml);
        unset($xml29->PurchaseOrderNumber);

        $xml30 = new SimpleXMLElement($xml);
        unset($xml30->PackageId);

        $xml31 = new SimpleXMLElement($xml);
        unset($xml31->PromisedShippingTime);

        $xml32 = new SimpleXMLElement($xml);
        unset($xml32->ExtraAttributes);

        $xml33 = new SimpleXMLElement($xml);
        unset($xml33->ShippingProviderType);

        $xml34 = new SimpleXMLElement($xml);
        unset($xml34->CreatedAt);

        $xml35 = new SimpleXMLElement($xml);
        unset($xml35->UpdatedAt);

        $xml36 = new SimpleXMLElement($xml);
        unset($xml36->ReturnStatus);

        return [
            [$xml1, 'The xml structure is not valid for a OrderItem. The property OrderItemId should exist.'],
            [$xml2, 'The xml structure is not valid for a OrderItem. The property ShopId should exist.'],
            [$xml3, 'The xml structure is not valid for a OrderItem. The property OrderId should exist.'],
            [$xml4, 'The xml structure is not valid for a OrderItem. The property Name should exist.'],
            [$xml5, 'The xml structure is not valid for a OrderItem. The property Sku should exist.'],
            [$xml6, 'The xml structure is not valid for a OrderItem. The property Variation should exist.'],
            [$xml7, 'The xml structure is not valid for a OrderItem. The property ShopSku should exist.'],
            [$xml8, 'The xml structure is not valid for a OrderItem. The property ShippingType should exist.'],
            [$xml9, 'The xml structure is not valid for a OrderItem. The property ItemPrice should exist.'],
            [$xml10, 'The xml structure is not valid for a OrderItem. The property PaidPrice should exist.'],
            [$xml11, 'The xml structure is not valid for a OrderItem. The property Currency should exist.'],
            [$xml12, 'The xml structure is not valid for a OrderItem. The property WalletCredits should exist.'],
            [$xml13, 'The xml structure is not valid for a OrderItem. The property TaxAmount should exist.'],
            [$xml14, 'The xml structure is not valid for a OrderItem. The property CodCollectableAmount should exist.'],
            [$xml15, 'The xml structure is not valid for a OrderItem. The property ShippingAmount should exist.'],
            [$xml16, 'The xml structure is not valid for a OrderItem. The property ShippingServiceCost should exist.'],
            [$xml17, 'The xml structure is not valid for a OrderItem. The property VoucherAmount should exist.'],
            [$xml18, 'The xml structure is not valid for a OrderItem. The property VoucherCode should exist.'],
            [$xml19, 'The xml structure is not valid for a OrderItem. The property Status should exist.'],
            [$xml20, 'The xml structure is not valid for a OrderItem. The property IsProcessable should exist.'],
            [$xml21, 'The xml structure is not valid for a OrderItem. The property ShipmentProvider should exist.'],
            [$xml22, 'The xml structure is not valid for a OrderItem. The property IsDigital should exist.'],
            [$xml23, 'The xml structure is not valid for a OrderItem. The property DigitalDeliveryInfo should exist.'],
            [$xml24, 'The xml structure is not valid for a OrderItem. The property TrackingCode should exist.'],
            [$xml25, 'The xml structure is not valid for a OrderItem. The property TrackingCodePre should exist.'],
            [$xml26, 'The xml structure is not valid for a OrderItem. The property Reason should exist.'],
            [$xml27, 'The xml structure is not valid for a OrderItem. The property ReasonDetail should exist.'],
            [$xml28, 'The xml structure is not valid for a OrderItem. The property PurchaseOrderId should exist.'],
            [$xml29, 'The xml structure is not valid for a OrderItem. The property PurchaseOrderNumber should exist.'],
            [$xml30, 'The xml structure is not valid for a OrderItem. The property PackageId should exist.'],
            [$xml31, 'The xml structure is not valid for a OrderItem. The property PromisedShippingTime should exist.'],
            [$xml32, 'The xml structure is not valid for a OrderItem. The property ExtraAttributes should exist.'],
            [$xml33, 'The xml structure is not valid for a OrderItem. The property ShippingProviderType should exist.'],
            [$xml34, 'The xml structure is not valid for a OrderItem. The property CreatedAt should exist.'],
            [$xml35, 'The xml structure is not valid for a OrderItem. The property UpdatedAt should exist.'],
            [$xml36, 'The xml structure is not valid for a OrderItem. The property ReturnStatus should exist.'],
        ];
    }

    public function testItReturnsAJsonRepresentation(): void
    {
        $orderItemId = '6750999';
        $shopId = '7208215';
        $orderId = '4758978';
        $name = 'MEGIR 5006 RELOJ ACERO INOXIDABLE ROSA';
        $sku = 'DJFKLJOEDKLFJ';
        $variation = 'Talla Única';
        $shopSku = 'ME803FA0UEI9YLCO-6073653';
        $shippingType = 'Dropshipping';
        $itemPrice = '89900.00';
        $paidPrice = '89900.00';
        $currency = 'COP';
        $walletCredits = '0.00';
        $taxAmount = '0.00';
        $codCollectableAmount = 12;
        $shippingAmount = '0.00';
        $shippingServiceCost = '7000.00';
        $voucherAmount = '0';
        $voucherCode = 'msxnwinsiqni';
        $status = 'pending';
        $isProcessable = true;
        $shipmentProvider = 'LOGISTICA';
        $isDigital = false;
        $digitalDeliveryInfo = 'OK';
        $trackingCode = '1000414030800';
        $trackingCodePre = '1000414030800';
        $reason = 'OK';
        $reasonDetail = 'OK';
        $purchaseOrderId = '0';
        $purchaseOrderNumber = '23781748362';
        $packageId = '1000414030800';
        $shippingProviderType = 'express';
        $returnStatus = 'approved';

        $simpleXml = simplexml_load_string(
            sprintf(
                '<OrderItem>
                    <OrderItemId>%s</OrderItemId>
                    <ShopId>%s</ShopId>
                    <OrderId>%s</OrderId>
                    <Name>%s</Name>
                    <Sku>%s</Sku>
                    <Variation>%s</Variation>
                    <ShopSku>%s</ShopSku>
                    <ShippingType>%s</ShippingType>
                    <ItemPrice>%s</ItemPrice>
                    <PaidPrice>%s</PaidPrice>
                    <Currency>%s</Currency>
                    <WalletCredits>%s</WalletCredits>
                    <TaxAmount>%s</TaxAmount>
                    <CodCollectableAmount>%s</CodCollectableAmount>
                    <ShippingAmount></ShippingAmount>
                    <ShippingServiceCost>%s</ShippingServiceCost>
                    <VoucherAmount>%s</VoucherAmount>
                    <VoucherCode>%s</VoucherCode>
                    <Status>%s</Status>
                    <IsProcessable>%d</IsProcessable>
                    <ShipmentProvider>%s</ShipmentProvider>
                    <IsDigital>%d</IsDigital>
                    <DigitalDeliveryInfo>%s</DigitalDeliveryInfo>
                    <TrackingCode>%s</TrackingCode>
                    <TrackingCodePre>%s</TrackingCodePre>
                    <Reason>%s</Reason>
                    <ReasonDetail>%s</ReasonDetail>
                    <PurchaseOrderId>%s</PurchaseOrderId>
                    <PurchaseOrderNumber>%s</PurchaseOrderNumber>
                    <PackageId>%s</PackageId>
                    <PromisedShippingTime>2018-10-16 20:00:00</PromisedShippingTime>
                    <ExtraAttributes/>
                    <ShippingProviderType>%s</ShippingProviderType>
                    <CreatedAt>2018-10-13 23:08:34</CreatedAt>
                    <UpdatedAt>2018-10-14 13:30:50</UpdatedAt>
                    <ReturnStatus>%s</ReturnStatus>
               </OrderItem>',
                $orderItemId,
                $shopId,
                $orderId,
                $name,
                $sku,
                $variation,
                $shopSku,
                $shippingType,
                $itemPrice,
                $paidPrice,
                $currency,
                $walletCredits,
                $taxAmount,
                $codCollectableAmount,
                $shippingServiceCost,
                $voucherAmount,
                $voucherCode,
                $status,
                (int) $isProcessable,
                $shipmentProvider,
                (int) $isDigital,
                $digitalDeliveryInfo,
                $trackingCode,
                $trackingCodePre,
                $reason,
                $reasonDetail,
                $purchaseOrderId,
                $purchaseOrderNumber,
                $packageId,
                $shippingProviderType,
                $returnStatus
            )
        );

        $orderItem = OrderItemFactory::make($simpleXml);

        $expectedJson = sprintf(
            '{"orderItemId":%d,"shopId":%d,"orderId":%d,"name":"%s","sku":"%s","variation":"%s","shopSku":"%s","shippingType":"%s","itemPrice":%d,"paidPrice":%d,"currency":"%s","walletCredits": %d,"taxAmount": %d,"codCollectableAmount":%d,"shippingAmount":%d,"shippingServiceCost":%d,"voucherAmount":%d,"voucherCode":"%s","status":"%s","isProcessable":%s,"shipmentProvider":"%s","isDigital":%s,"digitalDeliveryInfo":"%s","trackingCode":"%s","trackingCodePre":"%s","reason":"%s","reasonDetail":"%s","purchaseOrderId":%d,"purchaseOrderNumber":"%s","packageId":"%s","promisedShippingTime":%s,"extraAttributes": null,"shippingProviderType":"%s","createdAt":%s,"updatedAt":%s,"returnStatus":"%s"}',
            $orderItemId,
            $shopId,
            $orderId,
            $name,
            $sku,
            $variation,
            $shopSku,
            $shippingType,
            $itemPrice,
            $paidPrice,
            $currency,
            $walletCredits,
            $taxAmount,
            $codCollectableAmount,
            $shippingAmount,
            $shippingServiceCost,
            $voucherAmount,
            $voucherCode,
            $status,
            $isProcessable ? 'true' : 'false',
            $shipmentProvider,
            $isDigital ? 'true' : 'false',
            $digitalDeliveryInfo,
            $trackingCode,
            $trackingCodePre,
            $reason,
            $reasonDetail,
            $purchaseOrderId,
            $purchaseOrderNumber,
            $packageId,
            Json::encode($orderItem->getPromisedShippingTime()),
            $shippingProviderType,
            Json::encode($orderItem->getCreatedAt()),
            Json::encode($orderItem->getUpdatedAt()),
            $returnStatus
        );

        $this->assertJsonStringEqualsJsonString($expectedJson, Json::encode($orderItem));
    }
}

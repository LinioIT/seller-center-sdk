<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Order;

use DateTimeImmutable;
use Linio\Component\Util\Json;
use Linio\SellerCenter\Exception\InvalidXmlStructureException;
use Linio\SellerCenter\Factory\Xml\Order\OrderItemFactory;
use Linio\SellerCenter\LinioTestCase;
use Linio\SellerCenter\Model\Order\OrderItem;

class OrderItemTest extends LinioTestCase
{
    protected $orderItemId = 6750999;
    protected $shopId = 7208215;
    protected $orderId = 4758978;
    protected $name = 'MEGIR 5006 RELOJ ACERO INOXIDABLE ROSA';
    protected $sku = 'DJFKLJOEDKLFJ';
    protected $variation = 'Talla Única';
    protected $shopSku = 'ME803FA0UEI9YLCO-6073653';
    protected $shippingType = 'Dropshipping';
    protected $itemPrice = 89900.00;
    protected $paidPrice = 89900.00;
    protected $currency = 'COP';
    protected $walletCredits = 0.00;
    protected $taxAmount = 0.00;
    protected $codCollectableAmount = 12;
    protected $shippingAmount = 0.00;
    protected $shippingServiceCost = 7000.00;
    protected $voucherAmount = 0;
    protected $voucherCode = 'msxnwinsiqni';
    protected $status = 'pending';
    protected $isProcessable = true;
    protected $shipmentProvider = 'LOGISTICA';
    protected $isDigital = false;
    protected $digitalDeliveryInfo = 'OK';
    protected $trackingCode = '1000414030800';
    protected $trackingCodePre = '1000414030800';
    protected $reason = 'OK';
    protected $reasonDetail = 'OK';
    protected $purchaseOrderId = 0;
    protected $purchaseOrderNumber = '23781748362';
    protected $packageId = '1000414030800';
    protected $shippingProviderType = 'express';
    protected $returnStatus = 'approved';

    public function testItReturnsTheValueWithEachAccessor(): void
    {
        $simpleXml = simplexml_load_string($this->createXmlStringForOrderItems());

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
        $this->assertEquals($orderItem->getSalesType(), (string) $simpleXml->SalesType);
    }

    public function testItReturnsTheNullWithoutAnExtraAttributeTag(): void
    {
        $simpleXml = simplexml_load_string($this->createXmlStringForOrderItems());
        $simpleXml->CodCollectableAmount = null;
        $simpleXml->VoucherCode = null;
        $simpleXml->DigitalDeliveryInfo = null;
        $simpleXml->TrackingCodePre = null;
        $simpleXml->Reason = null;
        $simpleXml->ReasonDetail = null;
        $simpleXml->PurchaseOrderNumber = null;
        $simpleXml->CreatedAt = null;
        $simpleXml->UpdatedAt = null;
        $simpleXml->ReturnStatus = null;

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
        $simpleXml = simplexml_load_string($this->createXmlStringForOrderItems());

        $orderItem = OrderItemFactory::makeFromStatus($simpleXml);

        $this->assertInstanceOf(OrderItem::class, $orderItem);
        $this->assertEquals((int) $simpleXml->OrderItemId, $orderItem->getOrderItemId());
        $this->assertEquals((int) $simpleXml->PurchaseOrderId, $orderItem->getPurchaseOrderId());
        $this->assertEquals((string) $simpleXml->PurchaseOrderNumber, $orderItem->getPurchaseOrderNumber());
        $this->assertEquals((string) $simpleXml->PackageId, $orderItem->getPackageId());
    }

    /**
     * @dataProvider simpleXmlElementsWithoutAParameter
     */
    public function testItThrowsAExceptionWithoutAPropertyInTheXml(string $property, bool $fromStatus): void
    {
        $this->expectException(InvalidXmlStructureException::class);

        $this->expectExceptionMessage(
            sprintf(
                'The xml structure is not valid for a OrderItem. The property %s should exist.',
                $property
            )
        );

        $simpleXml = simplexml_load_string($this->createXmlStringForOrderItems());

        unset($simpleXml->{$property});
        if ($fromStatus) {
            OrderItemFactory::makeFromStatus($simpleXml);
        } else {
            OrderItemFactory::make($simpleXml);
        }
    }

    public function testItReturnsAJsonRepresentation(): void
    {
        $simpleXml = simplexml_load_string($this->createXmlStringForOrderItems());

        $orderItem = OrderItemFactory::make($simpleXml);
        $expectedJson = Json::decode($this->getSchema('Order/OrderItem.json'));

        $expectedJson['orderItemId'] = $this->orderItemId;
        $expectedJson['shopId'] = $this->shopId;
        $expectedJson['orderId'] = $this->orderId;
        $expectedJson['name'] = $this->name;
        $expectedJson['sku'] = $this->sku;
        $expectedJson['variation'] = $this->variation;
        $expectedJson['shopSku'] = $this->shopSku;
        $expectedJson['shippingType'] = $this->shippingType;
        $expectedJson['itemPrice'] = $this->itemPrice;
        $expectedJson['paidPrice'] = $this->paidPrice;
        $expectedJson['currency'] = $this->currency;
        $expectedJson['walletCredits'] = $this->walletCredits;
        $expectedJson['taxAmount'] = $this->taxAmount;
        $expectedJson['codCollectableAmount'] = $this->codCollectableAmount;
        $expectedJson['shippingAmount'] = $this->shippingAmount;
        $expectedJson['shippingServiceCost'] = $this->shippingServiceCost;
        $expectedJson['voucherAmount'] = $this->voucherAmount;
        $expectedJson['voucherCode'] = $this->voucherCode;
        $expectedJson['status'] = $this->status;
        $expectedJson['isProcessable'] = $this->isProcessable;
        $expectedJson['shipmentProvider'] = $this->shipmentProvider;
        $expectedJson['isDigital'] = $this->isDigital;
        $expectedJson['digitalDeliveryInfo'] = $this->digitalDeliveryInfo;
        $expectedJson['trackingCode'] = $this->trackingCode;
        $expectedJson['trackingCodePre'] = $this->trackingCodePre;
        $expectedJson['reason'] = $this->reason;
        $expectedJson['reasonDetail'] = $this->reasonDetail;
        $expectedJson['purchaseOrderId'] = $this->purchaseOrderId;
        $expectedJson['purchaseOrderNumber'] = $this->purchaseOrderNumber;
        $expectedJson['packageId'] = $this->packageId;
        $expectedJson['promisedShippingTime'] = $orderItem->getPromisedShippingTime();
        $expectedJson['shippingProviderType'] = $this->shippingProviderType;
        $expectedJson['createdAt'] = $orderItem->getCreatedAt();
        $expectedJson['updatedAt'] = $orderItem->getUpdatedAt();
        $expectedJson['returnStatus'] = $this->returnStatus;

        $this->assertJsonStringEqualsJsonString(Json::encode($expectedJson), Json::encode($orderItem));
    }

    public function simpleXmlElementsWithoutAParameter(): array
    {
        return [
            ['OrderItemId', false],
            ['ShopId', false],
            ['OrderId', false],
            ['Name', false],
            ['Sku', false],
            ['Variation', false],
            ['ShopSku', false],
            ['ShippingType', false],
            ['ItemPrice', false],
            ['PaidPrice', false],
            ['Currency', false],
            ['WalletCredits', false],
            ['TaxAmount', false],
            ['CodCollectableAmount', false],
            ['ShippingAmount', false],
            ['ShippingServiceCost', false],
            ['VoucherAmount', false],
            ['VoucherCode', false],
            ['Status', false],
            ['IsProcessable', false],
            ['ShipmentProvider', false],
            ['IsDigital', false],
            ['DigitalDeliveryInfo', false],
            ['TrackingCode', false],
            ['TrackingCodePre', false],
            ['Reason', false],
            ['ReasonDetail', false],
            ['PurchaseOrderId', false],
            ['PurchaseOrderNumber', false],
            ['PackageId', false],
            ['PromisedShippingTime', false],
            ['ExtraAttributes', false],
            ['ShippingProviderType', false],
            ['CreatedAt', false],
            ['UpdatedAt', false],
            ['ReturnStatus', false],
            ['PurchaseOrderId', true],
            ['PurchaseOrderNumber', true],
        ];
    }

    public function createXmlStringForOrderItems(string $schema = 'Order/OrderItem.xml'): string
    {
        return sprintf(
            $this->getSchema($schema),
            $this->orderItemId,
            $this->shopId,
            $this->orderId,
            $this->name,
            $this->sku,
            $this->variation,
            $this->shopSku,
            $this->shippingType,
            $this->itemPrice,
            $this->paidPrice,
            $this->currency,
            $this->walletCredits,
            $this->taxAmount,
            $this->codCollectableAmount,
            $this->shippingServiceCost,
            $this->voucherAmount,
            $this->voucherCode,
            $this->status,
            (int) $this->isProcessable,
            $this->shipmentProvider,
            (int) $this->isDigital,
            $this->digitalDeliveryInfo,
            $this->trackingCode,
            $this->trackingCodePre,
            $this->reason,
            $this->reasonDetail,
            $this->purchaseOrderId,
            $this->purchaseOrderNumber,
            $this->packageId,
            $this->shippingProviderType,
            $this->returnStatus
        );
    }
}

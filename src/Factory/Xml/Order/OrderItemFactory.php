<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Xml\Order;

use DateTimeImmutable;
use Linio\Component\Util\Json;
use Linio\SellerCenter\Exception\InvalidXmlStructureException;
use Linio\SellerCenter\Model\Order\OrderItem;
use SimpleXMLElement;

class OrderItemFactory
{
    public static function make(SimpleXMLElement $element): OrderItem
    {
        if (!property_exists($element, 'OrderItemId')) {
            throw new InvalidXmlStructureException('OrderItem', 'OrderItemId');
        }

        if (!property_exists($element, 'ShopId')) {
            throw new InvalidXmlStructureException('OrderItem', 'ShopId');
        }

        if (!property_exists($element, 'OrderId')) {
            throw new InvalidXmlStructureException('OrderItem', 'OrderId');
        }

        if (!property_exists($element, 'Name')) {
            throw new InvalidXmlStructureException('OrderItem', 'Name');
        }

        if (!property_exists($element, 'Sku')) {
            throw new InvalidXmlStructureException('OrderItem', 'Sku');
        }

        if (!property_exists($element, 'Variation')) {
            throw new InvalidXmlStructureException('OrderItem', 'Variation');
        }

        if (!property_exists($element, 'ShopSku')) {
            throw new InvalidXmlStructureException('OrderItem', 'ShopSku');
        }

        if (!property_exists($element, 'ShippingType')) {
            throw new InvalidXmlStructureException('OrderItem', 'ShippingType');
        }

        if (!property_exists($element, 'ItemPrice')) {
            throw new InvalidXmlStructureException('OrderItem', 'ItemPrice');
        }

        if (!property_exists($element, 'PaidPrice')) {
            throw new InvalidXmlStructureException('OrderItem', 'PaidPrice');
        }

        if (!property_exists($element, 'Currency')) {
            throw new InvalidXmlStructureException('OrderItem', 'Currency');
        }

        if (!property_exists($element, 'WalletCredits')) {
            throw new InvalidXmlStructureException('OrderItem', 'WalletCredits');
        }

        if (!property_exists($element, 'TaxAmount')) {
            throw new InvalidXmlStructureException('OrderItem', 'TaxAmount');
        }

        if (!property_exists($element, 'CodCollectableAmount')) {
            throw new InvalidXmlStructureException('OrderItem', 'CodCollectableAmount');
        }

        if (!property_exists($element, 'ShippingAmount')) {
            throw new InvalidXmlStructureException('OrderItem', 'ShippingAmount');
        }

        if (!property_exists($element, 'ShippingServiceCost')) {
            throw new InvalidXmlStructureException('OrderItem', 'ShippingServiceCost');
        }

        if (!property_exists($element, 'VoucherAmount')) {
            throw new InvalidXmlStructureException('OrderItem', 'VoucherAmount');
        }

        if (!property_exists($element, 'VoucherCode')) {
            throw new InvalidXmlStructureException('OrderItem', 'VoucherCode');
        }

        if (!property_exists($element, 'Status')) {
            throw new InvalidXmlStructureException('OrderItem', 'Status');
        }

        if (!property_exists($element, 'IsProcessable')) {
            throw new InvalidXmlStructureException('OrderItem', 'IsProcessable');
        }

        if (!property_exists($element, 'ShipmentProvider')) {
            throw new InvalidXmlStructureException('OrderItem', 'ShipmentProvider');
        }

        if (!property_exists($element, 'IsDigital')) {
            throw new InvalidXmlStructureException('OrderItem', 'IsDigital');
        }

        if (!property_exists($element, 'DigitalDeliveryInfo')) {
            throw new InvalidXmlStructureException('OrderItem', 'DigitalDeliveryInfo');
        }

        if (!property_exists($element, 'TrackingCode')) {
            throw new InvalidXmlStructureException('OrderItem', 'TrackingCode');
        }

        if (!property_exists($element, 'TrackingCodePre')) {
            throw new InvalidXmlStructureException('OrderItem', 'TrackingCodePre');
        }

        if (!property_exists($element, 'Reason')) {
            throw new InvalidXmlStructureException('OrderItem', 'Reason');
        }

        if (!property_exists($element, 'ReasonDetail')) {
            throw new InvalidXmlStructureException('OrderItem', 'ReasonDetail');
        }

        if (!property_exists($element, 'PurchaseOrderId')) {
            throw new InvalidXmlStructureException('OrderItem', 'PurchaseOrderId');
        }

        if (!property_exists($element, 'PurchaseOrderNumber')) {
            throw new InvalidXmlStructureException('OrderItem', 'PurchaseOrderNumber');
        }

        if (!property_exists($element, 'PackageId')) {
            throw new InvalidXmlStructureException('OrderItem', 'PackageId');
        }

        if (!property_exists($element, 'PromisedShippingTime')) {
            throw new InvalidXmlStructureException('OrderItem', 'PromisedShippingTime');
        }

        if (!property_exists($element, 'ExtraAttributes')) {
            throw new InvalidXmlStructureException('OrderItem', 'ExtraAttributes');
        }

        if (!property_exists($element, 'ShippingProviderType')) {
            throw new InvalidXmlStructureException('OrderItem', 'ShippingProviderType');
        }

        if (!property_exists($element, 'CreatedAt')) {
            throw new InvalidXmlStructureException('OrderItem', 'CreatedAt');
        }

        if (!property_exists($element, 'UpdatedAt')) {
            throw new InvalidXmlStructureException('OrderItem', 'UpdatedAt');
        }

        if (!property_exists($element, 'ReturnStatus')) {
            throw new InvalidXmlStructureException('OrderItem', 'ReturnStatus');
        }

        $isProcessable = !empty($element->IsProcessable);
        $isDigital = !empty($element->IsDigital);

        $dateTime = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', (string) $element->PromisedShippingTime);
        $promisedShippingTime = !empty($dateTime) ? $dateTime : null;

        $extraAttributes = Json::decode((string) $element->ExtraAttributes);

        $dateTime = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', (string) $element->CreatedAt);
        $createdAt = !empty($dateTime) ? $dateTime : null;

        $dateTime = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', (string) $element->UpdatedAt);
        $updatedAt = !empty($dateTime) ? $dateTime : null;

        return OrderItem::fromOrderItem(
            (int) $element->OrderItemId,
            (int) $element->ShopId,
            (int) $element->OrderId,
            (string) $element->Name,
            (string) $element->Sku,
            (string) $element->Variation,
            (string) $element->ShopSku,
            (string) $element->ShippingType,
            (float) $element->ItemPrice,
            (float) $element->PaidPrice,
            (string) $element->Currency,
            (float) $element->WalletCredits,
            (float) $element->TaxAmount,
            (float) $element->CodCollectableAmount,
            (float) $element->ShippingAmount,
            (float) $element->ShippingServiceCost,
            (int) $element->VoucherAmount,
            (string) $element->VoucherCode,
            (string) $element->Status,
            $isProcessable,
            (string) $element->ShipmentProvider,
            $isDigital,
            (string) $element->DigitalDeliveryInfo,
            (string) $element->TrackingCode,
            (string) $element->TrackingCodePre,
            (string) $element->Reason,
            (string) $element->ReasonDetail,
            (int) $element->PurchaseOrderId,
            (string) $element->PurchaseOrderNumber,
            (string) $element->PackageId,
            $promisedShippingTime,
            $extraAttributes,
            (string) $element->ShippingProviderType,
            $createdAt,
            $updatedAt,
            (string) $element->ReturnStatus
        );
    }

    public static function makeFromStatus(SimpleXMLElement $element): OrderItem
    {
        if (!property_exists($element, 'PurchaseOrderId')) {
            throw new InvalidXmlStructureException('OrderItem', 'PurchaseOrderId');
        }

        if (!property_exists($element, 'PurchaseOrderNumber')) {
            throw new InvalidXmlStructureException('OrderItem', 'PurchaseOrderNumber');
        }

        $orderItemId = (int) $element->OrderItemId;
        $packageId = empty($element->PackageId) ? null : (string) $element->PackageId;

        return OrderItem::fromStatus(
            $orderItemId,
            (int) $element->PurchaseOrderId,
            (string) $element->PurchaseOrderNumber,
            $packageId
        );
    }
}

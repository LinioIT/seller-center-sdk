<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Xml\Order;

use DateTimeImmutable;
use Linio\Component\Util\Json;
use Linio\SellerCenter\Model\Order\OrderItem;
use Linio\SellerCenter\Validator\XmlStructureValidator;
use SimpleXMLElement;

class OrderItemFactory
{
    private const XML_MODEL = 'OrderItem';
    private const REQUIRED_FIELDS = [
        'OrderItemId',
        'ShopId',
        'OrderId',
        'Name',
        'Sku',
        'Variation',
        'ShopSku',
        'ShippingType',
        'ItemPrice',
        'PaidPrice',
        'Currency',
        'WalletCredits',
        'TaxAmount',
        'CodCollectableAmount',
        'ShippingAmount',
        'ShippingServiceCost',
        'VoucherAmount',
        'VoucherCode',
        'Status',
        'IsProcessable',
        'ShipmentProvider',
        'IsDigital',
        'DigitalDeliveryInfo',
        'TrackingCode',
        'TrackingCodePre',
        'Reason',
        'ReasonDetail',
        'PurchaseOrderId',
        'PurchaseOrderNumber',
        'PackageId',
        'PromisedShippingTime',
        'ExtraAttributes',
        'ShippingProviderType',
        'CreatedAt',
        'UpdatedAt',
        'ReturnStatus',
    ];
    private const REQUIRED_FIELDS_FROM_STATUS = [
        'PurchaseOrderId',
        'PurchaseOrderNumber',
    ];

    private const REQUIRED_FIELDS_FROM_IMEI_STATUS = [
        'OrderItemId',
        'Imei',
        'Status',
    ];

    public static function make(SimpleXMLElement $element): OrderItem
    {
        XmlStructureValidator::validateStructure($element, self::XML_MODEL, self::REQUIRED_FIELDS);

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
            (string) $element->ReturnStatus,
            (string) $element->SalesType ?? null,
            (string) $element->Imei ?? null
        );
    }

    public static function makeFromStatus(SimpleXMLElement $element): OrderItem
    {
        XmlStructureValidator::validateStructure($element, self::XML_MODEL, self::REQUIRED_FIELDS_FROM_STATUS);

        $orderItemId = (int) $element->OrderItemId;
        $packageId = empty($element->PackageId) ? null : (string) $element->PackageId;

        return OrderItem::fromStatus(
            $orderItemId,
            (int) $element->PurchaseOrderId,
            (string) $element->PurchaseOrderNumber,
            $packageId
        );
    }

    public static function makeFromImeiStatus(SimpleXMLElement $element): OrderItem
    {
        XmlStructureValidator::validateStructure($element, self::XML_MODEL, self::REQUIRED_FIELDS_FROM_IMEI_STATUS);

        $orderItemId = (int) $element->OrderItemId;
        $imei = empty($element->Imei) ? null : (string) $element->Imei;
        $message = empty($element->Message) ? null : (string) $element->Message;

        return OrderItem::fromImeiStatus(
            $orderItemId,
            $imei,
            (string) $element->Status,
            $message
        );
    }
}

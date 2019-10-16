<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Xml\Order;

use DateTimeImmutable;
use Linio\SellerCenter\Exception\InvalidXmlStructureException;
use Linio\SellerCenter\Model\Order\Order;
use SimpleXMLElement;

class OrderFactory
{
    public static function make(SimpleXMLElement $element): Order
    {
        if (!property_exists($element, 'OrderId')) {
            throw new InvalidXmlStructureException('Order', 'OrderId');
        }

        if (!property_exists($element, 'CustomerFirstName')) {
            throw new InvalidXmlStructureException('Order', 'CustomerFirstName');
        }

        if (!property_exists($element, 'CustomerLastName')) {
            throw new InvalidXmlStructureException('Order', 'CustomerLastName');
        }

        if (!property_exists($element, 'OrderNumber')) {
            throw new InvalidXmlStructureException('Order', 'OrderNumber');
        }

        if (!property_exists($element, 'PaymentMethod')) {
            throw new InvalidXmlStructureException('Order', 'PaymentMethod');
        }

        if (!property_exists($element, 'Remarks')) {
            throw new InvalidXmlStructureException('Order', 'Remarks');
        }

        if (!property_exists($element, 'DeliveryInfo')) {
            throw new InvalidXmlStructureException('Order', 'DeliveryInfo');
        }

        if (!property_exists($element, 'Price')) {
            throw new InvalidXmlStructureException('Order', 'Price');
        }

        if (!property_exists($element, 'GiftOption')) {
            throw new InvalidXmlStructureException('Order', 'GiftOption');
        }

        if (!property_exists($element, 'GiftMessage')) {
            throw new InvalidXmlStructureException('Order', 'GiftMessage');
        }

        if (!property_exists($element, 'VoucherCode')) {
            throw new InvalidXmlStructureException('Order', 'VoucherCode');
        }

        if (!property_exists($element, 'CreatedAt')) {
            throw new InvalidXmlStructureException('Order', 'CreatedAt');
        }

        if (!property_exists($element, 'UpdatedAt')) {
            throw new InvalidXmlStructureException('Order', 'UpdatedAt');
        }

        if (!property_exists($element, 'AddressUpdatedAt')) {
            throw new InvalidXmlStructureException('Order', 'AddressUpdatedAt');
        }
        if (!property_exists($element, 'AddressBilling')) {
            throw new InvalidXmlStructureException('Order', 'AddressBilling');
        }

        if (!property_exists($element, 'AddressShipping')) {
            throw new InvalidXmlStructureException('Order', 'AddressShipping');
        }

        if (!property_exists($element, 'NationalRegistrationNumber')) {
            throw new InvalidXmlStructureException('Order', 'NationalRegistrationNumber');
        }

        if (!property_exists($element, 'ItemsCount')) {
            throw new InvalidXmlStructureException('Order', 'ItemsCount');
        }

        if (!property_exists($element, 'PromisedShippingTime')) {
            throw new InvalidXmlStructureException('Order', 'PromisedShippingTime');
        }

        if (!property_exists($element, 'ExtraAttributes')) {
            throw new InvalidXmlStructureException('Order', 'ExtraAttributes');
        }

        if (!property_exists($element, 'Statuses')) {
            throw new InvalidXmlStructureException('Order', 'Statuses');
        }

        $giftOption = !empty($element->GiftOption);

        $dateTime = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', (string) $element->CreatedAt);
        $createdAt = !empty($dateTime) ? $dateTime : null;

        $dateTime = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', (string) $element->UpdatedAt);
        $updatedAt = !empty($dateTime) ? $dateTime : null;

        $dateTime = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', (string) $element->AddressUpdatedAt);
        $addressUpdatedAt = !empty($dateTime) ? $dateTime : null;

        $addressBilling = AddressFactory::make($element->AddressBilling);

        $addressShipping = AddressFactory::make($element->AddressShipping);

        $dateTime = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', (string) $element->PromisedShippingTime);
        $promisedShippingTime = !empty($dateTime) ? $dateTime : null;

        $statuses = [];
        foreach ($element->Statuses->Status as $status) {
            array_push($statuses, (string) $status);
        }

        return Order::fromData(
            (int) $element->OrderId,
            (int) $element->OrderNumber,
            (string) $element->CustomerFirstName,
            (string) $element->CustomerLastName,
            (string) $element->PaymentMethod,
            (string) $element->Remarks,
            (string) $element->DeliveryInfo,
            (float) $element->Price,
            $giftOption,
            (string) $element->GiftMessage,
            (string) $element->VoucherCode,
            $createdAt,
            $updatedAt,
            $addressUpdatedAt,
            $addressBilling,
            $addressShipping,
            (string) $element->NationalRegistrationNumber,
            (int) $element->ItemsCount,
            $promisedShippingTime,
            (string) $element->ExtraAttributes,
            $statuses
        );
    }
}

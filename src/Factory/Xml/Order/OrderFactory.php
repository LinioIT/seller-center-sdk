<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Xml\Order;

use DateTimeImmutable;
use Linio\SellerCenter\Contract\BusinessUnitOperatorCodes;
use Linio\SellerCenter\Exception\InvalidDomainException;
use Linio\SellerCenter\Model\Order\Order;
use Linio\SellerCenter\Validator\XmlStructureValidator;
use SimpleXMLElement;

class OrderFactory
{
    private const XML_MODEL = 'Order';
    private const REQUIRED_FIELDS = [
        'OrderId',
        'CustomerFirstName',
        'CustomerLastName',
        'OrderNumber',
        'PaymentMethod',
        'Remarks',
        'DeliveryInfo',
        'Price',
        'GiftOption',
        'GiftMessage',
        'VoucherCode',
        'CreatedAt',
        'UpdatedAt',
        'AddressUpdatedAt',
        'AddressBilling',
        'AddressShipping',
        'PromisedShippingTime',
        'ItemsCount',
        'ExtraAttributes',
        'Statuses',
        'InvoiceRequired',
    ];

    public static function make(SimpleXMLElement $element): Order
    {
        XmlStructureValidator::validateStructure($element, self::XML_MODEL, self::REQUIRED_FIELDS);

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

        $nationalRegistrationNumber = !empty($element->NationalRegistrationNumber) ? (string) $element->NationalRegistrationNumber : null;

        $statuses = [];
        foreach ($element->Statuses->Status as $status) {
            array_push($statuses, (string) $status);
        }

        $operatorCode = (string) $element->OperatorCode ?? null;
        if (!empty($operatorCode) && !in_array(strtolower($operatorCode), BusinessUnitOperatorCodes::OPERATOR_CODES)) {
            throw new InvalidDomainException('OperatorCode');
        }

        $orderNumber = is_numeric((string) $element->OrderNumber) ? (int) $element->OrderNumber : (string) $element->OrderNumber;

        $shippingType = (string) $element->ShippingType ?? null;
        $invoiceRequired = $element->InvoiceRequired == 'true';

        return Order::fromData(
            (int) $element->OrderId,
            $orderNumber,
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
            $nationalRegistrationNumber,
            (int) $element->ItemsCount,
            $promisedShippingTime,
            (string) $element->ExtraAttributes,
            $statuses,
            $invoiceRequired,
            $shippingType,
            $operatorCode
        );
    }
}

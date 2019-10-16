<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Xml\Shipment;

use Linio\SellerCenter\Exception\InvalidXmlStructureException;
use Linio\SellerCenter\Model\Shipment\ShipmentProvider;
use SimpleXMLElement;

class ShipmentProviderFactory
{
    public static function make(SimpleXMLElement $element): ShipmentProvider
    {
        if (!property_exists($element, 'Name')) {
            throw new InvalidXmlStructureException('ShipmentProvider', 'Name');
        }

        $default = (bool) (int) $element->Default;
        $apiIntegration = (bool) (int) $element->ApiIntegration;
        $cod = (bool) (int) $element->Cod;
        $trackingCodeValidationRegex = (string) $element->TrackingCodeValidationRegex ?: null;
        $trackingCodeExample = (string) $element->TrackingCodeExample ?: null;
        $trackingUrl = (string) $element->TrackingUrl ?: null;
        $trackingCodeSetOnStep = (string) $element->TrackingCodeSetOnStep ?: null;

        $enabledDeliveryOptions = [];
        if (!empty($element->EnabledDeliveryOptions)) {
            foreach ($element->EnabledDeliveryOptions->DeliveryOption as $deliveryOption) {
                $enabledDeliveryOptions[] = (string) $deliveryOption;
            }
        }

        return new ShipmentProvider(
            (string) $element->Name,
            $default,
            $apiIntegration,
            $cod,
            $trackingCodeValidationRegex,
            $trackingCodeExample,
            $trackingUrl,
            $trackingCodeSetOnStep,
            $enabledDeliveryOptions
        );
    }
}

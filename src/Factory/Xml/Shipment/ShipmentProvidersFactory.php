<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Xml\Shipment;

use Linio\SellerCenter\Model\Shipment\ShipmentProviders;
use SimpleXMLElement;

class ShipmentProvidersFactory
{
    public static function make(SimpleXMLElement $element): ShipmentProviders
    {
        $shipmentProviders = new ShipmentProviders();

        foreach ($element->ShipmentProviders->ShipmentProvider as $item) {
            $shipmentProvider = ShipmentProviderFactory::make($item);
            $shipmentProviders->add($shipmentProvider);
        }

        return $shipmentProviders;
    }
}

<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Service;

use Linio\SellerCenter\Factory\Xml\Shipment\ShipmentProvidersFactory;
use Linio\SellerCenter\Model\Shipment\ShipmentProvider;

class ShipmentManager extends BaseManager
{
    /**
     * @return  ShipmentProvider[]
     */
    public function getShipmentProviders(bool $debug = true): array
    {
        $action = 'GetShipmentProviders';

        $parameters = $this->makeParametersForAction($action);

        $builtResponse = $this->executeAction(
            $action,
            $parameters,
            null,
            'GET',
            $debug
        );

        $shipmentProviders = ShipmentProvidersFactory::make($builtResponse->getBody());

        return $shipmentProviders->all();
    }
}

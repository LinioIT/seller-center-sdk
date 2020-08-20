<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Service;

use Linio\SellerCenter\Factory\Xml\Shipment\ShipmentProvidersFactory;

class ShipmentManager extends BaseManager
{
    private const GET_SHIPMENT_PROVIDERS_ACTION = 'GetShipmentProviders';

    public function getShipmentProviders(): array
    {
        $action = self::GET_SHIPMENT_PROVIDERS_ACTION;

        $requestId = $this->generateRequestId();

        $builtResponse = $this->executeAction($action, $requestId);

        $shipmentProviders = ShipmentProvidersFactory::make($builtResponse->getBody());

        $shipmentProvidersResponse = $shipmentProviders->all();

        $this->logger->info(
            sprintf(
                '%d::%s::APIResponse::SellerCenterSdk: %d shipment providers was recovered',
                $requestId,
                $action,
                count($shipmentProviders->all())
            )
        );

        return $shipmentProvidersResponse;
    }
}

<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model\Shipment;

use Linio\SellerCenter\Contract\CollectionInterface;

class ShipmentProviders implements CollectionInterface
{
    /**
     * @var ShipmentProvider[]
     */
    protected $collection = [];

    public function all(): array
    {
        return $this->collection;
    }

    public function add(ShipmentProvider $shipmentProvider): void
    {
        $this->collection[$shipmentProvider->getName()] = $shipmentProvider;
    }

    public function findByName(string $name): ?ShipmentProvider
    {
        if (array_key_exists($name, $this->collection)) {
            return $this->collection[$name];
        }

        return null;
    }
}

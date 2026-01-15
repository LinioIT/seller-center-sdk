<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Xml\Warehouse;

use DateTimeImmutable;
use Linio\SellerCenter\Exception\InvalidXmlStructureException;
use Linio\SellerCenter\Model\Warehouse\Warehouse;
use SimpleXMLElement;

class WarehouseFactory
{
    public const TRUE_STRING = 'true';

    public static function make(SimpleXMLElement $xml): Warehouse
    {
        if (!property_exists($xml, 'facilityId') || empty((string) $xml->facilityId)) {
            throw new InvalidXmlStructureException('Warehouse', 'facilityId');
        }

        if (!property_exists($xml, 'address')) {
            throw new InvalidXmlStructureException('Warehouse', 'address');
        }

        $facilityId = (string) $xml->facilityId;
        $name = (string) $xml->name;
        $sellerWarehouseId = (string) $xml->sellerWarehouseId ?: null;
        $warehouseType = (string) $xml->warehouseType ?: null;
        $updatedBy = (string) $xml->updatedBy ?: null;
        $nodeId = (string) $xml->nodeId ?: null;

        $isFbf = (string) $xml->isFbf === self::TRUE_STRING;
        $isDefault = (string) $xml->isDefault === self::TRUE_STRING;
        $isEnabled = (string) $xml->isEnabled === self::TRUE_STRING;
        $zoneAvailable = (string) $xml->zoneAvailable === self::TRUE_STRING;
        $isPickupStore = (string) $xml->isPickupStore === self::TRUE_STRING;

        $warehouseAddres = !empty($xml->address) ? WarehouseAddressFactory::make($xml->address) : null;
        $workingSchedules = !empty($xml->workingSchedule) ? WorkingSchedulesFactory::make($xml->workingSchedule) : null;

        $updatedAt = !empty($xml->updatedAt) ? new DateTimeImmutable((string) $xml->updatedAt) : null;
        $zoneUpdatedAt = !empty($xml->zoneUpdatedAt) ? new DateTimeImmutable((string) $xml->zoneUpdatedAt) : null;

        return new Warehouse(
            $name,
            $sellerWarehouseId,
            $facilityId,
            $warehouseType,
            $isFbf,
            $isDefault,
            $isPickupStore,
            $isEnabled,
            $updatedAt,
            $updatedBy,
            $warehouseAddres,
            $nodeId,
            $zoneAvailable,
            $zoneUpdatedAt,
            $workingSchedules
        );
    }
}

<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model\Warehouse;

use DateTimeInterface;
use JsonSerializable;
use stdClass;

class Warehouse implements JsonSerializable
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string|null
     */
    protected $sellerWarehouseId;

    /**
     * @var string
     */
    protected $facilityId;

    /**
     * @var string
     */
    protected $warehouseType;

    /**
     * @var bool
     */
    protected $isFbf;

    /**
     * @var bool
     */
    protected $isDefault;

    /**
     * @var bool
     */
    protected $isPickupStore;

    /**
     * @var bool
     */
    protected $isEnabled;

    /**
     * @var DateTimeInterface|null
     */
    protected $updatedAt;

    /**
     * @var string|null
     */
    protected $updatedBy;

    /**
     * @var WarehouseAddress|null
     */
    protected $warehouseAddress;

    /**
     * @var string|null
     */
    protected $nodeId;

    /**
     * @var bool
     */
    protected $zoneAvailable;

    /**
     * @var DateTimeInterface|null
     */
    protected $zoneUpdatedAt;

    /**
     * @var WorkingSchedules|null
     */
    protected $workingSchedule;

    public function __construct(
        string $name,
        ?string $sellerWarehouseId,
        string $facilityId,
        string $warehouseType,
        bool $isFbf,
        bool $isDefault,
        bool $isPickupStore,
        bool $isEnabled,
        ?DateTimeInterface $updatedAt,
        ?string $updatedBy,
        ?WarehouseAddress $warehouseAddress,
        ?string $nodeId,
        bool $zoneAvailable,
        ?DateTimeInterface $zoneUpdatedAt,
        ?WorkingSchedules $workingSchedule
    ) {
        $this->name = $name;
        $this->sellerWarehouseId = $sellerWarehouseId;
        $this->facilityId = $facilityId;
        $this->warehouseType = $warehouseType;
        $this->isFbf = $isFbf;
        $this->isDefault = $isDefault;
        $this->isPickupStore = $isPickupStore;
        $this->isEnabled = $isEnabled;
        $this->updatedBy = $updatedBy;
        $this->warehouseAddress = $warehouseAddress;
        $this->nodeId = $nodeId;
        $this->zoneAvailable = $zoneAvailable;
        $this->workingSchedule = $workingSchedule;
        $this->updatedAt = $updatedAt;
        $this->zoneUpdatedAt = $zoneUpdatedAt;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSellerWarehouseId(): ?string
    {
        return $this->sellerWarehouseId;
    }

    public function getFacilityId(): string
    {
        return $this->facilityId;
    }

    public function getWarehouseType(): string
    {
        return $this->warehouseType;
    }

    public function isFbf(): bool
    {
        return $this->isFbf;
    }

    public function isDefault(): bool
    {
        return $this->isDefault;
    }

    public function isPickupStore(): bool
    {
        return $this->isPickupStore;
    }

    public function isEnabled(): bool
    {
        return $this->isEnabled;
    }

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function getUpdatedBy(): ?string
    {
        return $this->updatedBy;
    }

    public function getWarehouseAddress(): ?WarehouseAddress
    {
        return $this->warehouseAddress;
    }

    public function getNodeId(): ?string
    {
        return $this->nodeId;
    }

    public function isAvailableZone(): bool
    {
        return $this->zoneAvailable;
    }

    public function getZoneUpdatedAt(): ?DateTimeInterface
    {
        return $this->zoneUpdatedAt;
    }

    public function getWorkingSchedule(): ?WorkingSchedules
    {
        return $this->workingSchedule;
    }

    public function jsonSerialize(): stdClass
    {
        $serialized = new stdClass();
        $serialized->name = $this->name;
        $serialized->sellerWarehouseId = $this->sellerWarehouseId;
        $serialized->facilityId = $this->facilityId;
        $serialized->isFbf = $this->isFbf;
        $serialized->isDefault = $this->isDefault;
        $serialized->isPickupStore = $this->isPickupStore;
        $serialized->isEnabled = $this->isEnabled;
        $serialized->updatedBy = $this->updatedBy;
        $serialized->warehouseAddress = $this->warehouseAddress;
        $serialized->nodeId = $this->nodeId;
        $serialized->zoneAvailable = $this->zoneAvailable;
        $serialized->workingSchedule = $this->workingSchedule;
        $serialized->updatedAt = $this->updatedAt;
        $serialized->zoneUpdatedAt = $this->zoneUpdatedAt;

        return $serialized;
    }
}

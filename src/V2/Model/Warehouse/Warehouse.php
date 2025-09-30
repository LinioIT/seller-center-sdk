<?php

declare(strict_types=1);

namespace Linio\SellerCenter\V2\Model\Warehouse;

use JsonSerializable;
use stdClass;

class Warehouse implements JsonSerializable
{
    /**
     * @var string
     */
    protected $facilityId;

    /**
     * @var string
     */
    protected $sellerWarehouseId;

    public function __construct(string $facilityId, string $sellerWarehouseId)
    {
        $this->facilityId = $facilityId;
        $this->sellerWarehouseId = $sellerWarehouseId;
    }

    public function getFacilityId(): string
    {
        return $this->facilityId;
    }

    public function getSellerWarehouseId(): string
    {
        return $this->sellerWarehouseId;
    }

    public function jsonSerialize(): stdClass
    {
        $serialized = new stdClass();
        $serialized->facilityId = $this->facilityId;
        $serialized->sellerWarehouseId = $this->sellerWarehouseId;

        return $serialized;
    }
}

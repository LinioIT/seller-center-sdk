<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model\Product;

use JsonSerializable;
use stdClass;

class ProductStock implements JsonSerializable
{
    /**
     * @var string $sellerSku
     */
    protected $sellerSku;

    /**
     * @var string $facilityId
     */
    protected $facilityId;

    /**
     * @var string $sellerWarehouseId
     */
    protected $sellerWarehouseId;

    /**
     * @var int $quantity
     */
    protected $quantity;

    public function __construct(string $sellerSku, int $quantity, string $facilityId, string $sellerWarehouseId)
    {
        $this->sellerSku = $sellerSku;
        $this->quantity = $quantity;
        $this->facilityId = $facilityId;
        $this->sellerWarehouseId = $sellerWarehouseId;
    }

    public function getSellerSku(): string
    {
        return $this->sellerSku;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
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
        $serialized->sellerSku = $this->sellerSku;
        $serialized->quantity = $this->quantity;
        $serialized->facilityId = $this->facilityId;
        $serialized->sellerWarehouseId = $this->sellerWarehouseId;

        return $serialized;
    }
}

<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model\Product;

use JsonSerializable;
use stdClass;

class ProductStock implements JsonSerializable
{
    /**
     * @var string
     */
    protected $sellerSku;

    /**
     * @var int
     */
    protected $quantity;

    /**
     * @var string|null
     */
    protected $facilityId;

    /**
     * @var string|null
     */
    protected $sellerWarehouseId;

    public function __construct(string $sellerSku, int $quantity, ?string $facilityId = null, ?string $sellerWarehouseId = null)
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

    public function getFacilityId(): ?string
    {
        return $this->facilityId;
    }

    public function getSellerWarehouseId(): ?string
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

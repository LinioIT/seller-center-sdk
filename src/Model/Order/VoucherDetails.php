<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model\Order;

/**
 * Order-level voucher breakdown (GetOrder / GetOrders -> VoucherDetails).
 */
class VoucherDetails implements \JsonSerializable
{
    /**
     * @var float
     */
    protected $cmrDiscount;

    /**
     * @var float
     */
    protected $otherDiscount;

    public function __construct(float $cmrDiscount, float $otherDiscount)
    {
        $this->cmrDiscount = $cmrDiscount;
        $this->otherDiscount = $otherDiscount;
    }

    public function getCmrDiscount(): float
    {
        return $this->cmrDiscount;
    }

    public function getOtherDiscount(): float
    {
        return $this->otherDiscount;
    }

    public function getTotal(): float
    {
        return round($this->cmrDiscount + $this->otherDiscount, 2);
    }

    public function jsonSerialize(): \stdClass
    {
        $serialized = new \stdClass();
        $serialized->cmrDiscount = $this->cmrDiscount;
        $serialized->otherDiscount = $this->otherDiscount;

        return $serialized;
    }
}

<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model\Order;

/**
 * Item-level voucher breakdown (GetOrderItem(s) -> VoucherAmountDetails).
 */
class VoucherAmountDetails implements \JsonSerializable
{
    /**
     * @var float
     */
    protected $cmrDiscount;

    /**
     * @var float
     */
    protected $otherDiscount;

    /**
     * @var float|null
     */
    protected $cmrDiscountSellerFundedPct;

    public function __construct(float $cmrDiscount, float $otherDiscount, ?float $cmrDiscountSellerFundedPct = null)
    {
        $this->cmrDiscount = $cmrDiscount;
        $this->otherDiscount = $otherDiscount;
        $this->cmrDiscountSellerFundedPct = $cmrDiscountSellerFundedPct;
    }

    public function getCmrDiscount(): float
    {
        return $this->cmrDiscount;
    }

    public function getOtherDiscount(): float
    {
        return $this->otherDiscount;
    }

    public function getCmrDiscountSellerFundedPct(): ?float
    {
        return $this->cmrDiscountSellerFundedPct;
    }

    /**
     * Amount of the CMR discount funded by the seller, or null when no percentage/CMR discount applies.
     */
    public function getCmrDiscountSellerFundedAmount(): ?float
    {
        if ($this->cmrDiscountSellerFundedPct === null || $this->cmrDiscount <= 0.0) {
            return null;
        }

        return round($this->cmrDiscount * $this->cmrDiscountSellerFundedPct / 100, 2);
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
        $serialized->cmrDiscountSellerFundedPct = $this->cmrDiscountSellerFundedPct;

        return $serialized;
    }
}

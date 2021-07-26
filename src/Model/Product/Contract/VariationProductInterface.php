<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model\Product\Contract;

use DateTimeInterface;

interface VariationProductInterface
{
    public function getAvailable(): int;

    public function getPrice(): float;

    public function getSalePrice(): ?float;

    public function getSaleStartDate(): ?DateTimeInterface;

    public function getSaleStartDateString(): ?string;

    public function getSaleEndDate(): ?DateTimeInterface;

    public function getSaleEndDateString(): ?string;

    public function getStatus(): string;

    public function setPrice(float $price): void;

    public function setSalePrice(?float $specialPrice): void;

    public function setSaleStartDate(?DateTimeInterface $specialFromDate): void;

    public function setSaleEndDate(?DateTimeInterface $specialToDate): void;

    public function setStatus(string $status): void;
}

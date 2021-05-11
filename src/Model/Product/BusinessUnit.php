<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model\Product;

use DateTimeInterface;
use JsonSerializable;
use Linio\SellerCenter\Contract\BusinessUnitOperatorCodes;
use Linio\SellerCenter\Contract\ProductStatus;
use Linio\SellerCenter\Exception\InvalidDomainException;
use stdClass;

class BusinessUnit implements JsonSerializable
{
    public const BUSINESS_UNITS = ['falabella', 'sodimac', 'linio'];

    /**
     * @var string|null
     */
    protected $businessUnit;

    /**
     * @var string
     */
    protected $operatorCode;

    /**
     * @var float
     */
    protected $price;

    /**
     * @var float|null
     */
    protected $specialPrice;

    /**
     * @var DateTimeInterface|null
     */
    protected $specialFromDate;

    /**
     * @var DateTimeInterface|null
     */
    protected $specialToDate;

    /**
     * @var int
     */
    protected $stock;

    /**
     * @var string
     */
    protected $status;

    /**
     * @var int
     */
    protected $isPublished;

    public function __construct(
        string $operatorCode,
        float $price,
        int $stock,
        string $status,
        int $isPublished,
        ?string $businessUnit = null,
        ?float $specialPrice = null,
        ?DateTimeInterface $specialFromDate = null,
        ?DateTimeInterface $specialToDate = null
    ) {
        $this->setOperatorCode($operatorCode);
        $this->setPrice($price);
        $this->setStock($stock);
        $this->setStatus($status);
        $this->setBusinessUnit($businessUnit);
        $this->setSalePrice($specialPrice);
        $this->setSaleStartDate($specialFromDate);
        $this->setSaleEndDate($specialToDate);
        $this->setIsPublished($isPublished);
    }

    public function getBusinessUnit(): ?string
    {
        return $this->businessUnit;
    }

    public function getOperatorCode(): string
    {
        return $this->operatorCode;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getSalePrice(): ?float
    {
        return $this->specialPrice;
    }

    public function getSaleStartDate(): ?DateTimeInterface
    {
        return $this->specialFromDate;
    }

    public function getSaleStartDateString(): ?string
    {
        if (empty($this->specialFromDate)) {
            return null;
        }

        return $this->specialFromDate->format('Y-m-d H:i:s');
    }

    public function getSaleEndDate(): ?DateTimeInterface
    {
        return $this->specialToDate;
    }

    public function getSaleEndDateString(): ?string
    {
        if (empty($this->specialToDate)) {
            return null;
        }

        return $this->specialToDate->format('Y-m-d H:i:s');
    }

    public function getStock(): int
    {
        return $this->stock;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getIsPublished(): int
    {
        return $this->isPublished;
    }

    public function setBusinessUnit(?string $businessUnit): void
    {
        if ($businessUnit !== null && !in_array(strtolower($businessUnit), self::BUSINESS_UNITS)) {
            throw new InvalidDomainException('BusinessUnit');
        }
        $this->businessUnit = $businessUnit;
    }

    public function setOperatorCode(string $operatorCode): void
    {
        if (!in_array($operatorCode, BusinessUnitOperatorCodes::OPERATOR_CODES)) {
            throw new InvalidDomainException('OperatorCode');
        }
        $this->operatorCode = $operatorCode;
    }

    public function setPrice(float $price): void
    {
        if ($price < 0) {
            throw new InvalidDomainException('Price');
        }
        $this->price = $price;
    }

    public function setSalePrice(?float $specialPrice): void
    {
        if ($specialPrice < 0 || $specialPrice > $this->price) {
            throw new InvalidDomainException('SpecialPrice');
        }
        $this->specialPrice = $specialPrice;
    }

    public function setSaleStartDate(?DateTimeInterface $specialFromDate): void
    {
        $this->specialFromDate = $specialFromDate;
    }

    public function setSaleEndDate(?DateTimeInterface $specialToDate): void
    {
        $this->specialToDate = $specialToDate;
    }

    public function setStock(int $stock): void
    {
        if ($stock < 0) {
            throw new InvalidDomainException('Stock');
        }
        $this->stock = $stock;
    }

    public function setStatus(string $status): void
    {
        if (in_array($status, ProductStatus::STATUS)) {
            $this->status = $status;
        } else {
            throw new InvalidDomainException('Status');
        }
    }

    public function setIsPublished(int $isPublished): void
    {
        $this->isPublished = $isPublished;
    }

    public function jsonSerialize(): stdClass
    {
        $serialized = new stdClass();
        $serialized->businessUnit = $this->businessUnit;
        $serialized->operatorCode = $this->operatorCode;
        $serialized->price = $this->price;
        $serialized->specialPrice = $this->specialPrice;
        $serialized->specialFromDate = $this->specialFromDate;
        $serialized->specialToDate = $this->specialToDate;
        $serialized->stock = $this->stock;
        $serialized->status = $this->status;
        $serialized->isPublished = $this->isPublished;

        return $serialized;
    }
}

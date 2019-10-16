<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model\Product;

use JsonSerializable;
use Linio\SellerCenter\Contract\ProductConditionTypes;
use Linio\SellerCenter\Exception\InvalidDomainException;
use stdClass;

class ProductData implements JsonSerializable
{
    /**
     * @var array
     */
    protected $attributes = [];

    public function __construct(string $conditionType, float $packageHeight, float $packageWidth, float $packageLength, float $packageWeight)
    {
        if (!in_array($conditionType, ProductConditionTypes::CONDITION_TYPES)) {
            throw new InvalidDomainException('ConditionType');
        }

        if ($packageHeight < 0) {
            throw new InvalidDomainException('PackageHeight');
        }

        if ($packageWidth < 0) {
            throw new InvalidDomainException('PackageWidth');
        }

        if ($packageLength < 0) {
            throw new InvalidDomainException('PackageLength');
        }

        if ($packageWeight < 0) {
            throw new InvalidDomainException('PackageWeight');
        }

        $this->attributes['ConditionType'] = $conditionType;
        $this->attributes['PackageHeight'] = $packageHeight;
        $this->attributes['PackageWidth'] = $packageWidth;
        $this->attributes['PackageLength'] = $packageLength;
        $this->attributes['PackageWeight'] = $packageWeight;
    }

    public function all(): array
    {
        return $this->attributes;
    }

    /**
     * @return mixed|null
     */
    public function getAttribute(string $attribute)
    {
        if (key_exists($attribute, $this->attributes)) {
            return $this->attributes[$attribute];
        }

        return null;
    }

    public function add(string $name, $value): void
    {
        if (!key_exists($name, $this->attributes)) {
            $this->attributes[$name] = $value;
        }
    }

    public function jsonSerialize(): stdClass
    {
        $serialized = new stdClass();
        foreach ($this->attributes as $attribute => $value) {
            $serialized->$attribute = $value;
        }

        return $serialized;
    }
}

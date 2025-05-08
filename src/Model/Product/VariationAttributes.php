<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model\Product;

use JsonSerializable;
use stdClass;

class VariationAttributes implements JsonSerializable
{
    /**
     * @var mixed[]
     */
    protected $variationAttributes = [];

    /**
     * @return mixed[]
     */
    public function all(): array
    {
        return $this->variationAttributes;
    }

    /**
     * @return mixed|null
     */
    public function getVariationAttribute(string $attribute)
    {
        if (key_exists($attribute, $this->variationAttributes)) {
            return $this->variationAttributes[$attribute];
        }

        return null;
    }

    /**
     * @phpstan-ignore-next-line
     */
    public function add(string $name, $value): void
    {
        if (!key_exists($name, $this->variationAttributes)) {
            $this->variationAttributes[$name] = $value;
        }
    }

    public function jsonSerialize(): stdClass
    {
        $serialized = new stdClass();
        foreach ($this->variationAttributes as $attribute => $value) {
            $serialized->$attribute = $value;
        }

        return $serialized;
    }
}

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

    private const ERROR_INVALID_KEY_VALUE = 'Both keys and values must be strings.';
    private const ERROR_INVALID_VALUE = 'The value must be a string and not empty.';

    /**
     * @param mixed[] $attributes
     */
    public function __construct(array $attributes = [])
    {
        foreach ($attributes as $key => $value) {
            foreach ($attributes as $key => $value) {
                if (!is_string($key) || !is_string($value)) {
                    throw new \InvalidArgumentException(self::ERROR_INVALID_KEY_VALUE);
                }

                $this->variationAttributes[ucfirst($key)] = $value;
            }
        }
    }

    /**
     * @return mixed[]
     */
    public function all(): array
    {
        return $this->variationAttributes;
    }

    public function getVariationAttribute(string $attribute): ?string
    {
        if (key_exists($attribute, $this->variationAttributes)) {
            return $this->variationAttributes[$attribute] ?? null;
        }

        return null;
    }

    public function add(string $name, ?string $value): void
    {
        if (!key_exists($name, $this->variationAttributes)) {
            $this->variationAttributes[ucfirst($name)] = $value;
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

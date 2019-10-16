<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model\Category;

use JsonSerializable;
use stdClass;

class CategoryAttribute implements JsonSerializable
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $feedName;

    /**
     * @var string
     */
    protected $label;

    /**
     * @var string
     */
    protected $globalIdentifier;

    /**
     * @var bool
     */
    protected $mandatory;

    /**
     * @var bool
     */
    protected $globalAttribute;

    /**
     * @var string|null
     */
    protected $description;

    /**
     * @var string|null
     */
    protected $productType;

    /**
     * @var string|null
     */
    protected $inputType;

    /**
     * @var string
     */
    protected $attributeType;

    /**
     * @var CategoryAttributeOptions
     */
    protected $options;

    /**
     * @var string|null
     */
    protected $groupName;

    /**
     * @var int|null
     */
    protected $maxLength;

    /**
     * @var string|null
     */
    protected $exampleValue;

    public function __construct(
        string $name,
        string $feedName,
        string $label,
        string $globalIdentifier,
        string $attributeType,
        CategoryAttributeOptions $options,
        bool $mandatory = false,
        bool $globalAttribute = false,
        ?string $description = null,
        ?string $productType = null,
        ?string $inputType = null,
        ?string $groupName = null,
        ?int $maxLength = null,
        ?string $exampleValue = null
    ) {
        $this->name = $name;
        $this->feedName = $feedName;
        $this->label = $label;
        $this->globalIdentifier = $globalIdentifier;
        $this->attributeType = $attributeType;
        $this->options = $options;
        $this->mandatory = $mandatory;
        $this->globalAttribute = $globalAttribute;
        $this->description = $description;
        $this->productType = $productType;
        $this->inputType = $inputType;
        $this->groupName = $groupName;
        $this->maxLength = $maxLength;
        $this->exampleValue = $exampleValue;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getFeedName(): string
    {
        return $this->feedName;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getGlobalIdentifier(): string
    {
        return $this->globalIdentifier;
    }

    public function isMandatory(): bool
    {
        return $this->mandatory;
    }

    public function isGlobalAttribute(): bool
    {
        return $this->globalAttribute;
    }

    public function getDescription(): ?string
    {
        return !empty($this->description) ? $this->description : null;
    }

    public function getProductType(): ?string
    {
        return $this->productType;
    }

    public function getInputType(): ?string
    {
        return $this->inputType;
    }

    public function getAttributeType(): string
    {
        return $this->attributeType;
    }

    public function getExampleValue(): ?string
    {
        return !empty($this->exampleValue) ? $this->exampleValue : null;
    }

    public function getOptions(): CategoryAttributeOptions
    {
        return $this->options;
    }

    public function getGroupName(): ?string
    {
        return $this->groupName;
    }

    public function getMaxLength(): ?int
    {
        return $this->maxLength;
    }

    public function jsonSerialize(): stdClass
    {
        $serialized = new stdClass();
        $serialized->name = $this->name;
        $serialized->feedName = $this->feedName;
        $serialized->label = $this->label;
        $serialized->globalIdentifier = $this->globalIdentifier;
        $serialized->mandatory = $this->mandatory;
        $serialized->globalAttribute = $this->globalAttribute;
        $serialized->description = $this->description;
        $serialized->productType = $this->productType;
        $serialized->inputType = $this->inputType;
        $serialized->attributeType = $this->attributeType;
        $serialized->options = $this->options;
        $serialized->groupName = $this->groupName;
        $serialized->maxLength = $this->maxLength;
        $serialized->exampleValue = $this->exampleValue;

        return $serialized;
    }
}

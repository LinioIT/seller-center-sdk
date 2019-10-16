<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model\Category;

use JsonSerializable;
use stdClass;

class AttributeSet implements JsonSerializable
{
    /**
     * @var int
     */
    protected $attributeSetId;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string|null
     */
    protected $globalIdentifier;

    /**
     * @var Categories
     */
    protected $categories;

    public function __construct(int $attributeSetId, string $name, ?string $globalIdentifier = null, ?Categories $categories = null)
    {
        $this->attributeSetId = $attributeSetId;
        $this->name = $name;
        $this->globalIdentifier = $globalIdentifier;
        $this->categories = !empty($categories) ? $categories : new Categories();
    }

    public function getAttributeSetId(): int
    {
        return $this->attributeSetId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getGlobalIdentifier(): ?string
    {
        return $this->globalIdentifier;
    }

    public function getCategories(): Categories
    {
        return $this->categories;
    }

    public function jsonSerialize(): stdClass
    {
        $serialized = new stdClass();
        $serialized->attributeSetId = $this->attributeSetId;
        $serialized->name = $this->name;
        $serialized->globalIdentifier = $this->globalIdentifier;
        $serialized->categories = $this->categories;

        return $serialized;
    }
}

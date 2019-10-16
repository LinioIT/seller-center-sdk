<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model\Category;

use JsonSerializable;
use stdClass;

class Category implements JsonSerializable
{
    /**
     * @var int|null
     */
    protected $categoryId;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string|null
     */
    protected $globalIdentifier;

    /**
     * @var int|null
     */
    protected $attributeSetId;

    /**
     * @var Category[]
     */
    protected $children = [];

    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * @return static
     */
    public static function build(
        int $categoryId,
        string $name,
        string $globalIdentifier,
        ?int $attributeSetId = null,
        array $children = null
    ): self {
        $category = new static();

        $category->categoryId = $categoryId;
        $category->name = $name;
        $category->globalIdentifier = $globalIdentifier;
        $category->attributeSetId = $attributeSetId ?: null;

        if (!empty($children)) {
            foreach ($children as $child) {
                $category->addChild($child);
            }
        }

        return $category;
    }

    /**
     * @return static
     */
    public static function fromId(int $id): self
    {
        $category = new static();

        $category->categoryId = $id;

        return $category;
    }

    /**
     * @return static
     */
    public static function fromName(string $name): self
    {
        $category = new static();

        $category->name = $name;

        return $category;
    }

    public function getId(): ?int
    {
        return $this->categoryId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getGlobalIdentifier(): ?string
    {
        return $this->globalIdentifier;
    }

    public function getAttributeSetId(): ?int
    {
        return $this->attributeSetId;
    }

    public function addChild(Category $category): void
    {
        $this->children[] = $category;
    }

    /**
     * @return Category[]
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    public function jsonSerialize(): stdClass
    {
        $serialized = new stdClass();
        $serialized->categoryId = $this->categoryId;
        $serialized->name = $this->name;
        $serialized->globalIdentifier = $this->globalIdentifier;
        $serialized->attributeSetId = $this->attributeSetId;
        $serialized->children = $this->children;
        $serialized->attributes = $this->attributes;

        return $serialized;
    }
}

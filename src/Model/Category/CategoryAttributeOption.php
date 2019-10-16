<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model\Category;

use JsonSerializable;
use stdClass;

class CategoryAttributeOption implements JsonSerializable
{
    /**
     * @var string|null
     */
    protected $globalIdentifier;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var bool
     */
    protected $default;

    public function __construct(?string $globalIdentifier, string $name, bool $default)
    {
        $this->globalIdentifier = $globalIdentifier ?: null;
        $this->name = $name;
        $this->default = $default;
    }

    public function getGlobalIdentifier(): ?string
    {
        return $this->globalIdentifier;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function isDefault(): bool
    {
        return $this->default;
    }

    public function jsonSerialize(): stdClass
    {
        $serialized = new stdClass();
        $serialized->globalIdentifier = $this->globalIdentifier;
        $serialized->name = $this->name;
        $serialized->default = $this->default;

        return $serialized;
    }
}

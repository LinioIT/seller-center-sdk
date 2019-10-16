<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model\Brand;

use JsonSerializable;
use Linio\SellerCenter\Exception\InvalidBrandIdException;
use Linio\SellerCenter\Exception\InvalidBrandNameException;
use stdClass;

class Brand implements JsonSerializable
{
    /**
     * @var int|null
     */
    protected $brandId;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string|null
     */
    protected $globalIdentifier;

    /**
     * @return static
     */
    public static function build(int $brandId, string $name, ?string $globalIdentifier): self
    {
        if (empty($brandId)) {
            throw new InvalidBrandIdException();
        }

        if (empty($name)) {
            throw new InvalidBrandNameException();
        }

        $brand = new static();

        $brand->name = $name;
        $brand->brandId = $brandId;
        $brand->globalIdentifier = $globalIdentifier ?? null;

        return $brand;
    }

    /**
     * @return static
     */
    public static function fromName(string $name): self
    {
        $brand = new static();

        $brand->name = $name;

        return $brand;
    }

    public function getBrandId(): ?int
    {
        return $this->brandId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getGlobalIdentifier(): ?string
    {
        return $this->globalIdentifier;
    }

    public function jsonSerialize(): stdClass
    {
        $serialized = new stdClass();
        $serialized->brandId = $this->brandId;
        $serialized->name = $this->name;
        $serialized->globalIdentifier = $this->globalIdentifier;

        return $serialized;
    }
}

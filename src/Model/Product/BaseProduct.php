<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model\Product;

use JsonSerializable;
use Linio\SellerCenter\Exception\EmptyArgumentException;
use Linio\SellerCenter\Model\Brand\Brand;
use Linio\SellerCenter\Model\Category\Categories;
use Linio\SellerCenter\Model\Category\Category;
use stdClass;

abstract class BaseProduct implements JsonSerializable
{
    /**
     * @var string
     */
    protected $sellerSku;

    /**
     * @var string|null
     */
    protected $newSellerSku = null;

    /**
     * @var string|null
     */
    protected $shopSku;

    /**
     * @var string|null
     */
    protected $productSin;

    /**
     * @var string|null
     */
    protected $parentSku;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $variation;

    /**
     * @var Category
     */
    protected $primaryCategory;

    /**
     * @var Categories
     */
    protected $categories;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var Brand
     */
    protected $brand;

    /**
     * @var string
     */
    protected $productId;

    /**
     * @var string|null
     */
    protected $taxClass;

    /**
     * @var ProductData
     */
    protected $productData;
    
    /**
     * @var string
     */
    protected $url;

    /**
     * @var Image
     */
    protected $mainImage;

    /**
     * @var Images
     */
    protected $images;

    abstract public function __construct();

    /**
     * @return mixed[]
     */
    abstract public function all(): array;

    /**
     * @return static
     */
    public static function fromSku(string $sku): self
    {
        $product = new static();
        $product->setSellerSku($sku);

        return $product;
    }

    public function getSellerSku(): string
    {
        return $this->sellerSku;
    }

    public function getNewSellerSku(): ?string
    {
        return $this->newSellerSku;
    }

    public function getShopSku(): ?string
    {
        return $this->shopSku;
    }

    public function getProductSin(): ?string
    {
        return $this->productSin;
    }

    public function getParentSku(): ?string
    {
        return $this->parentSku;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getVariation(): string
    {
        return $this->variation;
    }

    public function getPrimaryCategory(): Category
    {
        return $this->primaryCategory;
    }

    public function getCategories(): Categories
    {
        return $this->categories;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getBrand(): Brand
    {
        return $this->brand;
    }

    public function getProductId(): string
    {
        return $this->productId;
    }

    public function getTaxClass(): ?string
    {
        return $this->taxClass;
    }

    public function getProductData(): ProductData
    {
        return $this->productData;
    }

    public function getMainImage(): Image
    {
        return $this->mainImage;
    }

    public function getImages(): Images
    {
        return $this->images;
    }

    public function getUrl(): ?string
    {
        if (empty($this->url)) {
            return null;
        }

        return $this->url;
    }

    public function setSellerSku(string $sellerSku): void
    {
        $this->sellerSku = $sellerSku;
    }

    public function setNewSellerSku(string $newSellerSku): void
    {
        $this->newSellerSku = $newSellerSku;
    }

    public function setParentSku(string $parentSku): void
    {
        $this->parentSku = $parentSku;
    }

    public function setShopSku(string $sku): void
    {
        $this->shopSku = $sku;
    }

    public function setProductSin(string $productSin): void
    {
        $this->productSin = $productSin;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setVariation(string $variation): void
    {
        $this->variation = $variation;
    }

    public function setPrimaryCategory(Category $primaryCategory): void
    {
        $this->primaryCategory = $primaryCategory;
    }

    public function setCategories(Categories $categories): void
    {
        $this->categories = $categories;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function setBrand(Brand $brand): void
    {
        $this->brand = $brand;
    }

    public function setProductId(string $productId): void
    {
        $this->productId = $productId;
    }

    public function setTaxClass(?string $taxClass): void
    {
        $this->taxClass = $taxClass;
    }

    public function setProductData(ProductData $productData): void
    {
        $this->productData = $productData;
    }

    public function setMainImage(Image $mainImage): void
    {
        $this->mainImage = $mainImage;
    }

    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    public function attachImages(Images $images): void
    {
        $this->images = $images;
    }

    public function jsonSerialize(): stdClass
    {
        $serialized = new stdClass();
        $serialized->name = $this->name;
        $serialized->sellerSku = $this->sellerSku;
        $serialized->newSellerSku = $this->newSellerSku;
        $serialized->shopSku = $this->shopSku;
        $serialized->productSin = $this->productSin;
        $serialized->parentSku = $this->parentSku;
        $serialized->variation = $this->variation;
        $serialized->primaryCategory = $this->primaryCategory;
        $serialized->categories = $this->categories;
        $serialized->description = $this->description;
        $serialized->brand = $this->brand;
        $serialized->productId = $this->productId;
        $serialized->taxClass = $this->taxClass;
        $serialized->productData = $this->productData;
        $serialized->mainImage = $this->mainImage;
        $serialized->images = $this->images;

        return $serialized;
    }

    protected static function ValidateArguments(string $sellerSku, string $name, string $description, string $productId): void
    {
        if (empty($sellerSku)) {
            throw new EmptyArgumentException('SellerSku');
        }

        if (empty($name)) {
            throw new EmptyArgumentException('Name');
        }

        if (empty($description)) {
            throw new EmptyArgumentException('Description');
        }
        if (empty($productId)) {
            throw new EmptyArgumentException('ProductId');
        }
    }
}

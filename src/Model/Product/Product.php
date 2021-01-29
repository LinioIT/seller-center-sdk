<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model\Product;

use DateTimeInterface;
use JsonSerializable;
use Linio\SellerCenter\Contract\ProductStatus;
use Linio\SellerCenter\Exception\EmptyArgumentException;
use Linio\SellerCenter\Exception\InvalidDomainException;
use Linio\SellerCenter\Model\Brand\Brand;
use Linio\SellerCenter\Model\Category\Categories;
use Linio\SellerCenter\Model\Category\Category;
use stdClass;

class Product implements JsonSerializable
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
    protected $status;

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
     * @var float
     */
    protected $price;

    /**
     * @var float|null
     */
    protected $salePrice;

    /**
     * @var DateTimeInterface|null
     */
    protected $saleStartDate;

    /**
     * @var DateTimeInterface|null
     */
    protected $saleEndDate;

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
     * @var int
     */
    protected $quantity;

    /**
     * @var int
     */
    protected $available;

    /**
     * @var Image
     */
    protected $mainImage;

    /**
     * @var Images
     */
    protected $images;

    private function __construct()
    {
        $this->productData = new ProductData();
        $this->images = new Images();
    }

    /**
     * @return static
     */
    public static function fromBasicData(
        string $sellerSku,
        string $name,
        string $variation,
        Category $primaryCategory,
        string $description,
        Brand $brand,
        float $price,
        string $productId,
        ?string $taxClass,
        ProductData $productData,
        ?Images $images = null
    ): self {
        if (empty($sellerSku)) {
            throw new EmptyArgumentException('SellerSku');
        }

        if (empty($name)) {
            throw new EmptyArgumentException('Name');
        }

        if (empty($description)) {
            throw new EmptyArgumentException('Description');
        }

        if ($price <= 0) {
            throw new InvalidDomainException('Price');
        }

        if (empty($productId)) {
            throw new EmptyArgumentException('ProductId');
        }

        $product = new static();

        $product->setSellerSku($sellerSku);
        $product->setName($name);
        $product->setVariation($variation);
        $product->setPrimaryCategory($primaryCategory);
        $product->setDescription($description);
        $product->setBrand($brand);
        $product->setPrice($price);
        $product->setProductId($productId);
        $product->setTaxClass($taxClass);
        $product->setProductData($productData);

        $product->setStatus(ProductStatus::ACTIVE);
        $product->setQuantity(0);
        $product->setAvailable(0);
        $categories = new Categories();
        $product->setCategories($categories);

        if ($images) {
            $product->attachImages($images);
        }

        return $product;
    }

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

    public function getStatus(): string
    {
        return $this->status;
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

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getSalePrice(): ?float
    {
        return $this->salePrice;
    }

    public function getSaleStartDate(): ?DateTimeInterface
    {
        return $this->saleStartDate;
    }

    public function getSaleStartDateString(): ?string
    {
        if (empty($this->saleStartDate)) {
            return null;
        }

        return $this->saleStartDate->format('Y-m-d H:i:s');
    }

    public function getSaleEndDate(): ?DateTimeInterface
    {
        return $this->saleEndDate;
    }

    public function getSaleEndDateString(): ?string
    {
        if (empty($this->saleEndDate)) {
            return null;
        }

        return $this->saleEndDate->format('Y-m-d H:i:s');
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

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getAvailable(): int
    {
        return $this->available;
    }

    public function getMainImage(): Image
    {
        return $this->mainImage;
    }

    public function getImages(): Images
    {
        return $this->images;
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

    public function setStatus(string $status): void
    {
        if (in_array($status, ProductStatus::STATUS)) {
            $this->status = $status;
        }
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

    public function setPrice(float $price): void
    {
        if ($price > 0) {
            $this->price = $price;
        }
    }

    public function setSalePrice(float $salePrice): void
    {
        if ($salePrice > 0 && $salePrice < $this->price) {
            $this->salePrice = $salePrice;
        }
    }

    public function setSaleStartDate(DateTimeInterface $saleStartDate): void
    {
        $this->saleStartDate = $saleStartDate;
    }

    public function setSaleEndDate(DateTimeInterface $saleEndDate): void
    {
        $this->saleEndDate = $saleEndDate;
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

    public function setQuantity(int $quantity): void
    {
        if ($quantity >= 0) {
            $this->quantity = $quantity;
        }
    }

    public function setAvailable(int $available): void
    {
        if ($available <= $this->quantity && $available >= 0) {
            $this->available = $available;
        }
    }

    public function setMainImage(Image $mainImage): void
    {
        $this->mainImage = $mainImage;
    }

    public function attachImages(Images $images): void
    {
        $this->images = $images;
    }

    /**
     * @return mixed[]
     */
    public function all(): array
    {
        return [
            Attribute::FEED_SELLER_SKU => $this->sellerSku,
            Attribute::FEED_NEW_SELLER_SKU => $this->newSellerSku,
            Attribute::FEED_NAME => $this->name,
            Attribute::FEED_VARIATION => $this->variation,
            Attribute::FEED_STATUS => $this->status,
            Attribute::FEED_PRIMARY_CATEGORY => $this->primaryCategory,
            Attribute::FEED_CATEGORIES => $this->categories,
            Attribute::FEED_DESCRIPTION => $this->description,
            Attribute::FEED_BRAND => $this->brand,
            Attribute::FEED_PRICE => $this->price,
            Attribute::FEED_PRODUCT_ID => $this->productId,
            Attribute::FEED_TAX_CLASS => $this->taxClass,
            Attribute::FEED_PARENT_SKU => $this->parentSku,
            Attribute::FEED_QUANTITY => $this->quantity,
            Attribute::FEED_SALE_PRICE => $this->salePrice,
            Attribute::FEED_SALE_START_DATE => $this->getSaleStartDateString(),
            Attribute::FEED_SALE_END_DATE => $this->getSaleEndDateString(),
        ];
    }

    public function jsonSerialize(): stdClass
    {
        $serialized = new stdClass();
        $serialized->sellerSku = $this->sellerSku;
        $serialized->newSellerSku = $this->newSellerSku;
        $serialized->shopSku = $this->shopSku;
        $serialized->productSin = $this->productSin;
        $serialized->parentSku = $this->parentSku;
        $serialized->status = $this->status;
        $serialized->name = $this->name;
        $serialized->variation = $this->variation;
        $serialized->primaryCategory = $this->primaryCategory;
        $serialized->categories = $this->categories;
        $serialized->description = $this->description;
        $serialized->brand = $this->brand;
        $serialized->price = $this->price;
        $serialized->salePrice = $this->salePrice;
        $serialized->saleStartDate = $this->saleStartDate;
        $serialized->saleEndDate = $this->saleEndDate;
        $serialized->productId = $this->productId;
        $serialized->taxClass = $this->taxClass;
        $serialized->productData = $this->productData;
        $serialized->quantity = $this->quantity;
        $serialized->available = $this->available;
        $serialized->mainImage = $this->mainImage;
        $serialized->images = $this->images;

        return $serialized;
    }
}

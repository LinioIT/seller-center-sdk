<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model\Product;

use JsonSerializable;
use Linio\SellerCenter\Model\Brand\Brand;
use Linio\SellerCenter\Model\Category\Categories;
use Linio\SellerCenter\Model\Category\Category;
use Linio\SellerCenter\Model\Product\Contract\ProductInterface;
use stdClass;

class GlobalProduct extends BaseProduct implements JsonSerializable, ProductInterface
{
    /**
     * @var BusinessUnits
     */
    protected $businessUnits;

    /**
     * @var string|null
     */
    protected $qcStatus;

    /**
     * @var FashionData|null
     */
    protected $fashionData;

    public function __construct()
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
        ?string $variation,
        Category $primaryCategory,
        string $description,
        Brand $brand,
        BusinessUnits $businessUnits,
        string $productId,
        ?string $taxClass,
        ProductData $productData,
        ?Images $images = null,
        ?string $qcStatus = null,
        ?FashionData $fashionData = null
    ): self {
        self::ValidateArguments($sellerSku, $name, $description, $productId);

        $product = new static();

        $product->setSellerSku($sellerSku);
        $product->setName($name);
        $product->setPrimaryCategory($primaryCategory);
        $product->setDescription($description);
        $product->setBrand($brand);
        $product->setBusinessUnits($businessUnits);
        $product->setProductId($productId);
        $product->setTaxClass($taxClass);
        $product->setProductData($productData);

        $categories = new Categories();
        $product->setCategories($categories);

        if ($variation) {
            $product->setVariation($variation);
        }

        if ($images) {
            $product->attachImages($images);
        }

        if ($qcStatus) {
            $product->setQcStatus($qcStatus);
        }

        if ($fashionData) {
            $product->setFashionData($fashionData);
        }

        return $product;
    }

    public function getQcStatus(): ?string
    {
        if (empty($this->qcStatus)) {
            return null;
        }

        return $this->qcStatus;
    }

    public function getBusinessUnits(): ?BusinessUnits
    {
        return $this->businessUnits;
    }

    public function getFashionData(): ?FashionData
    {
        return $this->fashionData;
    }

    public function setQcStatus(string $qcStatus): void
    {
        $this->qcStatus = $qcStatus;
    }

    public function setBusinessUnits(BusinessUnits $businessUnits): void
    {
        $this->businessUnits = $businessUnits;
    }

    public function setFashionData(FashionData $fashionData): void
    {
        $this->fashionData = $fashionData;
    }

    /**
     * @return mixed[]
     */
    public function all(): array
    {
        $attributes = [
            Attribute::FEED_SELLER_SKU => $this->sellerSku,
            Attribute::FEED_NEW_SELLER_SKU => $this->newSellerSku,
            Attribute::FEED_NAME => $this->name,
            Attribute::FEED_PRIMARY_CATEGORY => $this->primaryCategory,
            Attribute::FEED_CATEGORIES => $this->categories,
            Attribute::FEED_DESCRIPTION => $this->description,
            Attribute::FEED_BRAND => $this->brand,
            Attribute::FEED_PRODUCT_ID => $this->productId,
            Attribute::FEED_TAX_CLASS => $this->taxClass,
            Attribute::FEED_PARENT_SKU => $this->parentSku,
        ];

        if (isset($this->variation)) {
            $attributes[Attribute::FEED_VARIATION] = $this->variation;
        }

        return $attributes;
    }

    public function jsonSerialize(): stdClass
    {
        $serialized = parent::jsonSerialize();

        $serialized->businessUnits = $this->businessUnits;
        $serialized->qcStatus = $this->qcStatus;

        return $serialized;
    }
}

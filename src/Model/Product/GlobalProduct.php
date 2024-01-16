<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model\Product;

use JsonSerializable;
use Linio\SellerCenter\Model\Brand\Brand;
use Linio\SellerCenter\Model\Category\Categories;
use Linio\SellerCenter\Model\Category\Category;
use Linio\SellerCenter\Model\Product\Contract\FashionInterface;
use Linio\SellerCenter\Model\Product\Contract\ProductInterface;
use stdClass;

class GlobalProduct extends BaseProduct implements JsonSerializable, ProductInterface, FashionInterface
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
     * @var string|null
     */
    protected $color;

    /**
     * @var string|null
     */
    protected $colorBasico;

    /**
     * @var string|null
     */
    protected $size;

    /**
     * @var string|null
     */
    protected $talla;

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
        ?string $qcStatus = null
    ): self {
        self::ValidateArguments($sellerSku, $name, $description);

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

        if (!empty($variation)) {
            $product->setVariation($variation);
        }

        if (!empty($images)) {
            $product->attachImages($images);
        }

        if (!empty($qcStatus)) {
            $product->setQcStatus($qcStatus);
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

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function getColorBasico(): ?string
    {
        return $this->colorBasico;
    }

    public function getSize(): ?string
    {
        return $this->size;
    }

    public function getTalla(): ?string
    {
        return $this->talla;
    }

    public function setQcStatus(string $qcStatus): void
    {
        $this->qcStatus = $qcStatus;
    }

    public function setBusinessUnits(BusinessUnits $businessUnits): void
    {
        $this->businessUnits = $businessUnits;
    }

    public function setColor(string $color): void
    {
        $this->color = $color;
    }

    public function setColorBasico(string $colorBasico): void
    {
        $this->colorBasico = $colorBasico;
    }

    public function setSize(string $size): void
    {
        $this->size = $size;
    }

    public function setTalla(string $talla): void
    {
        $this->talla = $talla;
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
            Attribute::FEED_PRIMARY_CATEGORY => $this->primaryCategory,
            Attribute::FEED_CATEGORIES => $this->categories,
            Attribute::FEED_DESCRIPTION => $this->description,
            Attribute::FEED_BRAND => $this->brand,
            Attribute::FEED_PRODUCT_ID => $this->productId,
            Attribute::FEED_TAX_CLASS => $this->taxClass,
            Attribute::FEED_PARENT_SKU => $this->parentSku,
            Attribute::FEED_VARIATION => $this->variation,
            Attribute::FEED_COLOR => $this->color,
            Attribute::FEED_BASIC_COLOR => $this->colorBasico,
            Attribute::FEED_SIZE => $this->size,
            Attribute::FEED_TALLA => $this->talla,
        ];
    }

    public function jsonSerialize(): stdClass
    {
        $serialized = parent::jsonSerialize();

        $serialized->businessUnits = $this->businessUnits;
        $serialized->qcStatus = $this->qcStatus;
        $serialized->color = $this->color;
        $serialized->colorBasico = $this->colorBasico;
        $serialized->size = $this->size;
        $serialized->talla = $this->talla;

        return $serialized;
    }
}

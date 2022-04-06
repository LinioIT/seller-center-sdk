<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model\Product;

use DateTimeInterface;
use JsonSerializable;
use Linio\SellerCenter\Contract\ProductStatus;
use Linio\SellerCenter\Exception\InvalidDomainException;
use Linio\SellerCenter\Model\Brand\Brand;
use Linio\SellerCenter\Model\Category\Categories;
use Linio\SellerCenter\Model\Category\Category;
use Linio\SellerCenter\Model\Product\Contract\ProductInterface;
use Linio\SellerCenter\Model\Product\Contract\VariationProductInterface;
use stdClass;

class Product extends BaseProduct implements JsonSerializable, ProductInterface, VariationProductInterface, ProductStatus
{
    /**
     * @var string
     */
    protected $status;

    /**
     * @var float|null
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
     * @var int
     */
    protected $quantity;

    /**
     * @var int|null
     */
    protected $available;

    /**
     * @var string[]
     */
    protected $overrideAttributes;

    public function __construct()
    {
        $this->productData = new ProductData();
        $this->images = new Images();
        $this->overrideAttributes = [];
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
        ?float $price,
        string $productId,
        ?string $taxClass,
        ProductData $productData,
        ?Images $images = null,
        ?array $overrideAttributes = []
    ): self {
        self::ValidateArguments($sellerSku, $name, $description, $productId);

        if ($price <= 0) {
            throw new InvalidDomainException('Price');
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

        $product->setOverrideAttributes($overrideAttributes);

        return $product;
    }

    public function getPrice(): ?float
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

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getAvailable(): ?int
    {
        return $this->available;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        if (in_array($status, ProductStatus::STATUS)) {
            $this->status = $status;
        }
    }

    public function setPrice(?float $price): void
    {
        if ($price > 0) {
            $this->price = $price;
        }
    }

    public function setSalePrice(?float $salePrice): void
    {
        if ($salePrice > 0 && $salePrice < $this->price) {
            $this->salePrice = $salePrice;
        }
    }

    public function setSaleStartDate(?DateTimeInterface $saleStartDate): void
    {
        $this->saleStartDate = $saleStartDate;
    }

    public function setSaleEndDate(?DateTimeInterface $saleEndDate): void
    {
        $this->saleEndDate = $saleEndDate;
    }

    public function setQuantity(int $quantity): void
    {
        if ($quantity >= 0) {
            $this->quantity = $quantity;
        }
    }

    public function setAvailable(?int $available): void
    {
        if ($available <= $this->quantity && $available >= 0) {
            $this->available = $available;
        }
    }

    public function setOverrideAttributes(array $overrideAttributes): void
    {
        $this->overrideAttributes = $overrideAttributes;
    }

    /**
     * @return string[]
     */
    public function getOverrideAttributes(): array
    {
        return $this->overrideAttributes;
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
        $serialized = parent::jsonSerialize();
        $serialized->status = $this->status;
        $serialized->price = $this->price;
        $serialized->salePrice = $this->salePrice;
        $serialized->saleStartDate = $this->saleStartDate;
        $serialized->saleEndDate = $this->saleEndDate;
        $serialized->quantity = $this->quantity;
        $serialized->available = $this->available;

        return $serialized;
    }
}

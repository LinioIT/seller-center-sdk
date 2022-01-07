<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model\Product\Contract;

use Linio\SellerCenter\Model\Brand\Brand;
use Linio\SellerCenter\Model\Category\Categories;
use Linio\SellerCenter\Model\Category\Category;
use Linio\SellerCenter\Model\Product\Image;
use Linio\SellerCenter\Model\Product\Images;
use Linio\SellerCenter\Model\Product\ProductData;

interface ProductInterface
{
    public function getSellerSku(): string;

    public function getNewSellerSku(): ?string;

    public function getShopSku(): ?string;

    public function getProductSin(): ?string;

    public function getParentSku(): ?string;

    public function getName(): string;

    public function getVariation(): ?string;

    public function getPrimaryCategory(): Category;

    public function getCategories(): Categories;

    public function getDescription(): string;

    public function getBrand(): Brand;

    public function getProductId(): string;

    public function getTaxClass(): ?string;

    public function getUrl(): ?string;

    public function getProductData(): ProductData;

    public function getMainImage(): Image;

    public function getImages(): Images;

    public function setSellerSku(string $sellerSku): void;

    public function setNewSellerSku(string $newSellerSku): void;

    public function setParentSku(string $parentSku): void;

    public function setShopSku(string $sku): void;

    public function setProductSin(string $productSin): void;

    public function setName(string $name): void;

    public function setVariation(string $variation): void;

    public function setPrimaryCategory(Category $primaryCategory): void;

    public function setCategories(Categories $categories): void;

    public function setDescription(string $description): void;

    public function setBrand(Brand $brand): void;

    public function setProductId(string $productId): void;

    public function setTaxClass(?string $taxClass): void;

    public function setUrl(string $url): void;

    public function setProductData(ProductData $productData): void;

    public function setMainImage(Image $mainImage): void;

    public function attachImages(Images $images): void;

    /**
     * @return mixed[]
     */
    public function all(): array;
}

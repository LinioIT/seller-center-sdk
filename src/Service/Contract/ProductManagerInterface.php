<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Service\Contract;

use DateTimeInterface;
use Linio\SellerCenter\Application\Parameters;
use Linio\SellerCenter\Model\Product\Contract\ProductInterface;
use Linio\SellerCenter\Model\Product\Products;
use Linio\SellerCenter\Response\FeedResponse;

interface ProductManagerInterface
{
    public const DEFAULT_LIMIT = 1000;
    public const DEFAULT_OFFSET = 0;
    public const DEFAULT_FILTER = 'all';

    public function productUpdate(Products $products, bool $debug = true): FeedResponse;

    public function productCreate(Products $products, bool $debug = true): FeedResponse;

    public function productRemove(Products $products, bool $debug = true): FeedResponse;

    /**
     * @param mixed[] $productImages
     */
    public function addImage(array $productImages, bool $debug = true): FeedResponse;

    /**
     * @return mixed[]
     */
    public function getProducts(Parameters $parameters, bool $debug = true): array;

    /**
     * @return ProductInterface[]
     */
    public function getAllProducts(
        int $limit = self::DEFAULT_LIMIT,
        int $offset = self::DEFAULT_OFFSET,
        bool $debug = true
    ): array;

    /**
     * @return ProductInterface[]
     */
    public function getProductsCreatedAfter(
        DateTimeInterface $createdAfter,
        int $limit = self::DEFAULT_LIMIT,
        int $offset = self::DEFAULT_OFFSET,
        bool $debug = true
    ): array;

    /**
     * @return ProductInterface[]
     */
    public function getProductsCreatedBefore(
        DateTimeInterface $createdBefore,
        int $limit = self::DEFAULT_LIMIT,
        int $offset = self::DEFAULT_OFFSET,
        bool $debug = true
    ): array;

    /**
     * @return ProductInterface[]
     */
    public function getProductsUpdatedAfter(
        DateTimeInterface $updatedAfter,
        int $limit = self::DEFAULT_LIMIT,
        int $offset = self::DEFAULT_LIMIT,
        bool $debug = true
    ): array;

    /**
     * @return ProductInterface[]
     */
    public function getProductsUpdatedBefore(
        DateTimeInterface $updatedBefore,
        int $limit = self::DEFAULT_LIMIT,
        int $offset = self::DEFAULT_OFFSET,
        bool $debug = true
    ): array;

    /**
     * @return ProductInterface[]
     */
    public function searchProducts(
        string $searchValue,
        int $limit = self::DEFAULT_LIMIT,
        int $offset = self::DEFAULT_OFFSET,
        bool $debug = true
    ): array;

    /**
     * @return ProductInterface[]
     */
    public function filterProducts(
        string $filter,
        int $limit = self::DEFAULT_LIMIT,
        int $offset = self::DEFAULT_OFFSET,
        bool $debug = true
    ): array;

    /**
     * @param mixed[] $skuSellerList
     *
     * @return ProductInterface[]
     */
    public function getProductsBySellerSku(
        array $skuSellerList,
        int $limit = self::DEFAULT_LIMIT,
        int $offset = self::DEFAULT_OFFSET,
        bool $debug = true
    ): array;

    /**
     * @param mixed[]|null $skuSellerList
     *
     * @return ProductInterface[]
     */
    public function getProductsFromParameters(
        ?DateTimeInterface $createdAfter = null,
        ?DateTimeInterface $createdBefore = null,
        ?string $search = null,
        string $filter = self::DEFAULT_FILTER,
        int $limit = self::DEFAULT_LIMIT,
        int $offset = self::DEFAULT_OFFSET,
        ?array $skuSellerList = null,
        ?DateTimeInterface $updateAfter = null,
        ?DateTimeInterface $updateBefore = null,
        bool $debug = true
    ): array;

    public function setListDimensions(Parameters &$parameters, int $limit, int $offset): void;
}

<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Service;

use DateTimeInterface;
use Linio\Component\Util\Json;
use Linio\SellerCenter\Application\Parameters;
use Linio\SellerCenter\Contract\ProductFilters;
use Linio\SellerCenter\Exception\EmptyArgumentException;
use Linio\SellerCenter\Factory\Xml\FeedResponseFactory;
use Linio\SellerCenter\Factory\Xml\Product\ProductsFactory;
use Linio\SellerCenter\Model\Product\Images;
use Linio\SellerCenter\Model\Product\Product;
use Linio\SellerCenter\Model\Product\Products;
use Linio\SellerCenter\Response\FeedResponse;
use Linio\SellerCenter\Transformer\Product\ProductsTransformer;

class ProductManager extends BaseManager
{
    public const DEFAULT_LIMIT = 1000;
    public const DEFAULT_OFFSET = 0;
    public const DEFAULT_FILTER = 'all';
    private const PRODUCT_CREATE_ACTION = 'ProductCreate';
    private const PRODUCT_UPDATE_ACTION = 'ProductUpdate';
    private const PRODUCT_REMOVE_ACTION = 'ProductRemove';
    private const IMAGE_ACTION = 'Image';
    private const GET_PRODUCTS_ACTION = 'GetProducts';

    public function productCreate(Products $products): FeedResponse
    {
        $action = self::PRODUCT_CREATE_ACTION;

        $xml = ProductsTransformer::asXmlString($products);

        $requestId = $this->generateRequestId();

        $builtResponse = $this->executeAction($action, $requestId, null, 'POST', $xml);

        $feedResponse = FeedResponseFactory::make($builtResponse->getHead());

        $this->logger->info(
            sprintf(
                '%d::%s::APIResponse::SellerCenterSdk: the product was created',
                $requestId,
                $action
            )
        );

        return $feedResponse;
    }

    public function productUpdate(Products $products): FeedResponse
    {
        $action = self::PRODUCT_UPDATE_ACTION;

        $xml = ProductsTransformer::asXmlString($products);

        $requestId = $this->generateRequestId();

        $builtResponse = $this->executeAction($action, $requestId, null, 'POST', $xml);

        $feedResponse = FeedResponseFactory::make($builtResponse->getHead());

        $this->logger->info(
            sprintf(
                '%d::%s::APIResponse::SellerCenterSdk: the product was updated',
                $requestId,
                $action
            )
        );

        return $feedResponse;
    }

    public function productRemove(Products $products): FeedResponse
    {
        $action = self::PRODUCT_REMOVE_ACTION;

        $xml = ProductsTransformer::skusAsXmlString($products);

        $requestId = $this->generateRequestId();

        $builtResponse = $this->executeAction($action, $requestId, null, 'POST', $xml);

        $feedResponse = FeedResponseFactory::make($builtResponse->getHead());

        $this->logger->info(
            sprintf(
                '%d::%s::APIResponse::SellerCenterSdk: the product was removed',
                $requestId,
                $action
            )
        );

        return $feedResponse;
    }

    public function addImage(array $productImages): FeedResponse
    {
        $action = self::IMAGE_ACTION;

        $products = new Products();

        foreach ($productImages as $sku => $images) {
            $product = Product::fromSku((string) $sku);
            $imagesCollection = new Images();
            $imagesCollection->addMany($images);

            $product->attachImages($imagesCollection);
            $products->add($product);
        }

        $xml = ProductsTransformer::imagesAsXmlString($products);

        $requestId = $this->generateRequestId();

        $builtResponse = $this->executeAction($action, $requestId, null, 'POST', $xml);

        $feedResponse = FeedResponseFactory::make($builtResponse->getHead());

        $this->logger->info(
            sprintf(
                '%d::%s::APIResponse::SellerCenterSdk: the images was added',
                $requestId,
                $action
            )
        );

        return $feedResponse;
    }

    protected function getProducts(Parameters $parameters): array
    {
        $action = self::GET_PRODUCTS_ACTION;

        $requestId = $this->generateRequestId();

        $builtResponse = $this->executeAction($action, $requestId, $parameters);

        $products = ProductsFactory::make($builtResponse->getBody());

        $productsResponse = array_values($products->all());

        $this->logger->info(
            sprintf(
                '%d::%s::APIResponse::SellerCenterSdk: %d products was recovered',
                $requestId,
                $action,
                count($products->all())
            )
        );

        return $productsResponse;
    }

    /**
     * @return Product[]
     */
    public function getAllProducts(int $limit = self::DEFAULT_LIMIT, int $offset = self::DEFAULT_OFFSET): array
    {
        $parameters = clone $this->parameters;

        $this->setListDimensions($parameters, $limit, $offset);

        return $this->getProducts($parameters);
    }

    /**
     * @return Product[]
     */
    public function getProductsCreatedAfter(
        DateTimeInterface $createdAfter,
        int $limit = self::DEFAULT_LIMIT,
        int $offset = self::DEFAULT_OFFSET
    ): array {
        $parameters = clone $this->parameters;

        $this->setListDimensions($parameters, $limit, $offset);

        $parameters->set(
            ['CreatedAfter' => $createdAfter->format(DATE_ATOM)]
        );

        return $this->getProducts($parameters);
    }

    /**
     * @return Product[]
     */
    public function getProductsCreatedBefore(
        DateTimeInterface $createdBefore,
        int $limit = self::DEFAULT_LIMIT,
        int $offset = self::DEFAULT_OFFSET
    ): array {
        $parameters = clone $this->parameters;

        $this->setListDimensions($parameters, $limit, $offset);

        $parameters->set(
            ['CreatedBefore' => $createdBefore->format(DATE_ATOM)]
        );

        return $this->getProducts($parameters);
    }

    /**
     * @return Product[]
     */
    public function getProductsUpdatedAfter(
        DateTimeInterface $updatedAfter,
        int $limit = self::DEFAULT_LIMIT,
        int $offset = self::DEFAULT_LIMIT
    ): array {
        $parameters = clone $this->parameters;

        $this->setListDimensions($parameters, $limit, $offset);

        $parameters->set(
            ['UpdatedAfter' => $updatedAfter->format(DATE_ATOM)]
        );

        return $this->getProducts($parameters);
    }

    /**
     * @return Product[]
     */
    public function getProductsUpdatedBefore(
        DateTimeInterface $updatedBefore,
        int $limit = self::DEFAULT_LIMIT,
        int $offset = self::DEFAULT_OFFSET
    ): array {
        $parameters = clone $this->parameters;

        $this->setListDimensions($parameters, $limit, $offset);

        $parameters->set(
            ['UpdatedBefore' => $updatedBefore->format(DATE_ATOM)]
        );

        return $this->getProducts($parameters);
    }

    /**
     * @return Product[]
     */
    public function searchProducts(
        string $searchValue,
        int $limit = self::DEFAULT_LIMIT,
        int $offset = self::DEFAULT_OFFSET
    ): array {
        $parameters = clone $this->parameters;

        $this->setListDimensions($parameters, $limit, $offset);

        $parameters->set(
            ['Search' => $searchValue]
        );

        return $this->getProducts($parameters);
    }

    /**
     * @return Product[]
     */
    public function filterProducts(
        string $filter,
        int $limit = self::DEFAULT_LIMIT,
        int $offset = self::DEFAULT_OFFSET
    ): array {
        $parameters = clone $this->parameters;

        $this->setListDimensions($parameters, $limit, $offset);

        if (!in_array($filter, ProductFilters::FILTERS)) {
            $filter = self::DEFAULT_FILTER;
        }

        $parameters->set(
            ['Filter' => $filter]
        );

        return $this->getProducts($parameters);
    }

    /**
     * @return Product[]
     */
    public function getProductsBySellerSku(
        array $skuSellerList,
        int $limit = self::DEFAULT_LIMIT,
        int $offset = self::DEFAULT_OFFSET
    ): array {
        $parameters = clone $this->parameters;

        if (empty($skuSellerList)) {
            throw new EmptyArgumentException('SkuSellerList');
        }

        $this->setListDimensions($parameters, $limit, $offset);

        $parameters->set(
            ['SkuSellerList' => Json::encode($skuSellerList)]
        );

        return $this->getProducts($parameters);
    }

    /**
     * @return Product[]
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
        ?DateTimeInterface $updateBefore = null
    ): array {
        $parameters = clone $this->parameters;

        $this->setListDimensions($parameters, $limit, $offset);

        if (!in_array($filter, ProductFilters::FILTERS)) {
            $filter = self::DEFAULT_FILTER;
        }

        $parameters->set(
            [
                'Search' => $search,
                'Filter' => $filter,
            ]
        );

        if (!empty($createdAfter)) {
            $parameters->set(['CreatedAfter' => $createdAfter->format(DATE_ATOM)]);
        }

        if (!empty($createdBefore)) {
            $parameters->set(['CreatedBefore' => $createdBefore->format(DATE_ATOM)]);
        }

        if (!empty($skuSellerList)) {
            $parameters->set(['SkuSellerList' => Json::encode($skuSellerList)]);
        }

        if (!empty($updateAfter)) {
            $parameters->set(['UpdateAfter' => $updateAfter->format(DATE_ATOM)]);
        }

        if (!empty($updateBefore)) {
            $parameters->set(['UpdateBefore' => $updateBefore->format(DATE_ATOM)]);
        }

        return $this->getProducts($parameters);
    }

    protected function setListDimensions(Parameters &$parameters, int $limit, int $offset): void
    {
        $verifiedLimit = $limit >= 1 ? $limit : self::DEFAULT_LIMIT;
        $verifiedOffset = $offset >= 1 ? $offset : self::DEFAULT_OFFSET;

        $parameters->set(
            [
                'Limit' => $verifiedLimit,
                'Offset' => $verifiedOffset,
            ]
        );
    }
}

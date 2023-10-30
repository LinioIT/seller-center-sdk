<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Service;

use DateTimeInterface;
use Linio\Component\Util\Json;
use Linio\SellerCenter\Application\Parameters;
use Linio\SellerCenter\Contract\ProductFilters;
use Linio\SellerCenter\Exception\EmptyArgumentException;
use Linio\SellerCenter\Factory\Xml\FeedResponseFactory;
use Linio\SellerCenter\Factory\Xml\Product\GlobalProductsFactory;
use Linio\SellerCenter\Model\Product\GlobalProduct;
use Linio\SellerCenter\Model\Product\Images;
use Linio\SellerCenter\Model\Product\Product;
use Linio\SellerCenter\Model\Product\Products;
use Linio\SellerCenter\Response\FeedResponse;
use Linio\SellerCenter\Service\Contract\ProductManagerInterface;
use Linio\SellerCenter\Transformer\Product\ProductsTransformer;

class GlobalProductManager extends BaseManager implements ProductManagerInterface
{
    public function productCreate(
        Products $products,
        bool $debug = true
    ): FeedResponse {
        return $this->executeProductAction(
            'ProductCreate',
            ProductsTransformer::asXmlString($products),
            $debug
        );
    }

    public function productUpdate(
        Products $products,
        bool $debug = true
    ): FeedResponse {
        return $this->executeProductAction(
            'ProductUpdate',
            ProductsTransformer::asXmlString($products),
            $debug
        );
    }

    public function productRemove(
        Products $products,
        bool $debug = true
    ): FeedResponse {
        return $this->executeProductAction(
            'ProductRemove',
            ProductsTransformer::skusAsXmlString($products),
            $debug
        );
    }

    protected function executeProductAction(
        string $action,
        string $xml,
        bool $debug = true
    ): FeedResponse {
        $parameters = $this->makeParametersForAction($action);

        $builtResponse = $this->executeAction(
            $action,
            $parameters,
            null,
            'POST',
            $debug,
            $xml
        );

        return FeedResponseFactory::make($builtResponse->getHead());
    }

    /**
     * @param mixed[] $productImages
     */
    public function addImage(
        array $productImages,
        bool $debug = true
    ): FeedResponse {
        $action = 'Image';

        $parameters = $this->makeParametersForAction($action);

        $products = new Products();

        foreach ($productImages as $sku => $images) {
            $product = Product::fromSku((string) $sku);
            $imagesCollection = new Images();
            $imagesCollection->addMany($images);

            $product->attachImages($imagesCollection);
            $products->add($product);
        }

        $xml = ProductsTransformer::imagesAsXmlString($products);

        $builtResponse = $this->executeAction(
            $action,
            $parameters,
            null,
            'POST',
            $debug,
            $xml
        );

        return FeedResponseFactory::make($builtResponse->getHead());
    }

    /**
     * @return mixed[]
     */
    public function getProducts(
        Parameters $parameters,
        bool $debug = true
    ): array {
        $action = 'GetProducts';

        $parameters->set(['Action' => $action]);

        $builtResponse = $this->executeAction(
            $action,
            $parameters,
            null,
            'GET',
            $debug
        );

        $products = GlobalProductsFactory::make($builtResponse->getBody(), $this->logger);

        return array_values($products->all());
    }

    /**
     * @return GlobalProduct[]
     */
    public function getAllProducts(
        int $limit = self::DEFAULT_LIMIT,
        int $offset = self::DEFAULT_OFFSET,
        bool $debug = true
    ): array {
        $parameters = clone $this->parameters;

        $this->setListDimensions($parameters, $limit, $offset);

        return $this->getProducts($parameters, $debug);
    }

    /**
     * @return GlobalProduct[]
     */
    public function getProductsCreatedAfter(
        DateTimeInterface $createdAfter,
        int $limit = self::DEFAULT_LIMIT,
        int $offset = self::DEFAULT_OFFSET,
        bool $debug = true
    ): array {
        $parameters = clone $this->parameters;

        $this->setListDimensions($parameters, $limit, $offset);

        $parameters->set(
            ['CreatedAfter' => $createdAfter->format(DATE_ATOM)]
        );

        return $this->getProducts($parameters, $debug);
    }

    /**
     * @return GlobalProduct[]
     */
    public function getProductsCreatedBefore(
        DateTimeInterface $createdBefore,
        int $limit = self::DEFAULT_LIMIT,
        int $offset = self::DEFAULT_OFFSET,
        bool $debug = true
    ): array {
        $parameters = clone $this->parameters;

        $this->setListDimensions($parameters, $limit, $offset);

        $parameters->set(
            ['CreatedBefore' => $createdBefore->format(DATE_ATOM)]
        );

        return $this->getProducts($parameters, $debug);
    }

    /**
     * @return GlobalProduct[]
     */
    public function getProductsUpdatedAfter(
        DateTimeInterface $updatedAfter,
        int $limit = self::DEFAULT_LIMIT,
        int $offset = self::DEFAULT_LIMIT,
        bool $debug = true
    ): array {
        $parameters = clone $this->parameters;

        $this->setListDimensions($parameters, $limit, $offset);

        $parameters->set(
            ['UpdatedAfter' => $updatedAfter->format(DATE_ATOM)]
        );

        return $this->getProducts($parameters, $debug);
    }

    /**
     * @return GlobalProduct[]
     */
    public function getProductsUpdatedBefore(
        DateTimeInterface $updatedBefore,
        int $limit = self::DEFAULT_LIMIT,
        int $offset = self::DEFAULT_OFFSET,
        bool $debug = true
    ): array {
        $parameters = clone $this->parameters;

        $this->setListDimensions($parameters, $limit, $offset);

        $parameters->set(
            ['UpdatedBefore' => $updatedBefore->format(DATE_ATOM)]
        );

        return $this->getProducts($parameters, $debug);
    }

    /**
     * @return GlobalProduct[]
     */
    public function searchProducts(
        string $searchValue,
        int $limit = self::DEFAULT_LIMIT,
        int $offset = self::DEFAULT_OFFSET,
        bool $debug = true
    ): array {
        $parameters = clone $this->parameters;

        $this->setListDimensions($parameters, $limit, $offset);

        $parameters->set(
            ['Search' => $searchValue]
        );

        return $this->getProducts($parameters, $debug);
    }

    /**
     * @return GlobalProduct[]
     */
    public function filterProducts(
        string $filter,
        int $limit = self::DEFAULT_LIMIT,
        int $offset = self::DEFAULT_OFFSET,
        bool $debug = true
    ): array {
        $parameters = clone $this->parameters;

        $this->setListDimensions($parameters, $limit, $offset);

        if (!in_array($filter, ProductFilters::FILTERS)) {
            $filter = self::DEFAULT_FILTER;
        }

        $parameters->set(
            ['Filter' => $filter]
        );

        return $this->getProducts($parameters, $debug);
    }

    /**
     * @param mixed[] $skuSellerList
     *
     * @return GlobalProduct[]
     */
    public function getProductsBySellerSku(
        array $skuSellerList,
        int $limit = self::DEFAULT_LIMIT,
        int $offset = self::DEFAULT_OFFSET,
        bool $debug = true
    ): array {
        $parameters = clone $this->parameters;

        if (empty($skuSellerList)) {
            throw new EmptyArgumentException('SkuSellerList');
        }

        $this->setListDimensions($parameters, $limit, $offset);

        $parameters->set(
            ['SkuSellerList' => Json::encode($skuSellerList)]
        );

        return $this->getProducts($parameters, $debug);
    }

    /**
     * @param mixed[]|null $skuSellerList
     *
     * @return GlobalProduct[]
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

        return $this->getProducts($parameters, $debug);
    }

    public function setListDimensions(Parameters &$parameters, int $limit, int $offset): void
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

<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Service;

use DateTimeInterface;
use Linio\Component\Util\Json;
use Linio\SellerCenter\Application\Parameters;
use Linio\SellerCenter\Application\Security\Signature;
use Linio\SellerCenter\Contract\ProductFilters;
use Linio\SellerCenter\Exception\EmptyArgumentException;
use Linio\SellerCenter\Factory\RequestFactory;
use Linio\SellerCenter\Factory\Xml\FeedResponseFactory;
use Linio\SellerCenter\Factory\Xml\Product\GlobalProductsFactory;
use Linio\SellerCenter\Formatter\LogMessageFormatter;
use Linio\SellerCenter\Model\Product\GlobalProduct;
use Linio\SellerCenter\Model\Product\Images;
use Linio\SellerCenter\Model\Product\Product;
use Linio\SellerCenter\Model\Product\Products;
use Linio\SellerCenter\Response\FeedResponse;
use Linio\SellerCenter\Response\HandleResponse;
use Linio\SellerCenter\Transformer\Product\ProductsTransformer;

class GlobalProductManager extends BaseManager
{
    public const DEFAULT_LIMIT = 1000;
    public const DEFAULT_OFFSET = 0;
    public const DEFAULT_FILTER = 'all';

    public function productCreate(Products $products): FeedResponse
    {
        $action = 'ProductCreate';

        $parameters = clone $this->parameters;
        $parameters->set(['Action' => $action]);
        $parameters->set([
            'Signature' => Signature::generate($parameters, $this->configuration->getKey())->get(),
        ]);

        $xml = ProductsTransformer::asXmlString($products);
        $requestHeaders = $this->generateRequestHeaders(['Content-type' => 'text/xml; charset=UTF8']);
        $requestId = $requestHeaders[self::REQUEST_ID_HEADER];

        $request = RequestFactory::make(
            'POST',
            $this->configuration->getEndpoint(),
            $requestHeaders,
            $xml
        );

        $this->logger->debug(
            LogMessageFormatter::fromAction($requestId, $action, LogMessageFormatter::TYPE_REQUEST),
            [
                'url' => (string) $request->getUri(),
                'method' => $request->getMethod(),
                'body' => (string) $request->getBody(),
                'parameters' => $parameters->all(),
            ]
        );

        $response = $this->client->send($request, [
            'query' => $parameters->all(),
        ]);

        $body = (string) $response->getBody();

        $this->logger->debug(
            LogMessageFormatter::fromAction($requestId, $action, LogMessageFormatter::TYPE_RESPONSE),
            [
                'statusCode' => $response->getStatusCode(),
                'body' => $body,
            ]
        );

        $builtResponse = HandleResponse::parse($body);

        $this->logger->debug(
            LogMessageFormatter::fromAction($requestId, $action, LogMessageFormatter::TYPE_BUILT_RESPONSE),
            [
                'head' => $builtResponse->getHead()->asXML(),
                'body' => $builtResponse->getBody()->asXML(),
            ]
        );

        $feedResponse = FeedResponseFactory::make($builtResponse->getHead());

        $this->logger->info(
            sprintf(
                '%d::%s::APIResponse::SellerCenterSdk: the product was created',
                $request->getHeaderLine('Request-ID'),
                $action
            )
        );

        return $feedResponse;
    }

    public function productUpdate(Products $products): FeedResponse
    {
        $action = 'ProductUpdate';

        $parameters = clone $this->parameters;
        $parameters->set(['Action' => $action]);
        $parameters->set([
            'Signature' => Signature::generate($parameters, $this->configuration->getKey())->get(),
        ]);

        $xml = ProductsTransformer::asXmlString($products);

        $requestHeaders = $this->generateRequestHeaders(['Content-type' => 'text/xml; charset=UTF8']);
        $requestId = $requestHeaders[self::REQUEST_ID_HEADER];

        $request = RequestFactory::make(
            'POST',
            $this->configuration->getEndpoint(),
            $requestHeaders,
            $xml
        );

        $this->logger->debug(
            LogMessageFormatter::fromAction($requestId, $action, LogMessageFormatter::TYPE_REQUEST),
            [
                'url' => (string) $request->getUri(),
                'method' => $request->getMethod(),
                'body' => (string) $request->getBody(),
                'parameters' => $parameters->all(),
            ]
        );

        $response = $this->client->send($request, [
            'query' => $parameters->all(),
        ]);

        $body = (string) $response->getBody();

        $this->logger->debug(
            LogMessageFormatter::fromAction($requestId, $action, LogMessageFormatter::TYPE_RESPONSE),
            [
                'statusCode' => $response->getStatusCode(),
                'body' => $body,
            ]
        );

        $builtResponse = HandleResponse::parse($body);

        $this->logger->debug(
            LogMessageFormatter::fromAction($requestId, $action, LogMessageFormatter::TYPE_BUILT_RESPONSE),
            [
                'head' => $builtResponse->getHead()->asXML(),
                'body' => $builtResponse->getBody()->asXML(),
            ]
        );

        $feedResponse = FeedResponseFactory::make($builtResponse->getHead());

        $this->logger->info(
            sprintf(
                '%d::%s::APIResponse::SellerCenterSdk: the product was updated',
                $request->getHeaderLine('Request-ID'),
                $action
            )
        );

        return $feedResponse;
    }

    public function productRemove(Products $products): FeedResponse
    {
        $action = 'ProductRemove';

        $parameters = clone $this->parameters;
        $parameters->set(['Action' => $action]);
        $parameters->set([
            'Signature' => Signature::generate($parameters, $this->configuration->getKey())->get(),
        ]);

        $xml = ProductsTransformer::skusAsXmlString($products);

        $requestHeaders = $this->generateRequestHeaders(['Content-type' => 'text/xml; charset=UTF8']);
        $requestId = $requestHeaders[self::REQUEST_ID_HEADER];

        $request = RequestFactory::make(
            'POST',
            $this->configuration->getEndpoint(),
            $requestHeaders,
            $xml
        );

        $this->logger->debug(
            LogMessageFormatter::fromAction($requestId, $action, LogMessageFormatter::TYPE_REQUEST),
            [
                'url' => (string) $request->getUri(),
                'method' => $request->getMethod(),
                'body' => (string) $request->getBody(),
                'parameters' => $parameters->all(),
            ]
        );

        $response = $this->client->send($request, [
            'query' => $parameters->all(),
        ]);

        $body = (string) $response->getBody();

        $this->logger->debug(
            LogMessageFormatter::fromAction($requestId, $action, LogMessageFormatter::TYPE_RESPONSE),
            [
                'statusCode' => $response->getStatusCode(),
                'body' => $body,
            ]
        );

        $builtResponse = HandleResponse::parse($body);

        $this->logger->debug(
            LogMessageFormatter::fromAction($requestId, $action, LogMessageFormatter::TYPE_BUILT_RESPONSE),
            [
                'head' => $builtResponse->getHead()->asXML(),
                'body' => $builtResponse->getBody()->asXML(),
            ]
        );

        $feedResponse = FeedResponseFactory::make($builtResponse->getHead());

        $this->logger->info(
            sprintf(
                '%d::%s::APIResponse::SellerCenterSdk: the product was removed',
                $request->getHeaderLine('Request-ID'),
                $action
            )
        );

        return $feedResponse;
    }

    /**
     * @param mixed[] $productImages
     */
    public function addImage(array $productImages): FeedResponse
    {
        $action = 'Image';

        $parameters = clone $this->parameters;
        $parameters->set(['Action' => $action]);
        $parameters->set([
            'Signature' => Signature::generate($parameters, $this->configuration->getKey())->get(),
        ]);

        $products = new Products();

        foreach ($productImages as $sku => $images) {
            $product = Product::fromSku((string) $sku);
            $imagesCollection = new Images();
            $imagesCollection->addMany($images);

            $product->attachImages($imagesCollection);
            $products->add($product);
        }

        $xml = ProductsTransformer::imagesAsXmlString($products);

        $requestHeaders = $this->generateRequestHeaders(['Content-type' => 'text/xml; charset=UTF8']);
        $requestId = $requestHeaders[self::REQUEST_ID_HEADER];

        $request = RequestFactory::make(
            'POST',
            $this->configuration->getEndpoint(),
            $requestHeaders,
            $xml
        );

        $this->logger->debug(
            LogMessageFormatter::fromAction($requestId, $action, LogMessageFormatter::TYPE_REQUEST),
            [
                'url' => (string) $request->getUri(),
                'method' => $request->getMethod(),
                'body' => (string) $request->getBody(),
                'parameters' => $parameters->all(),
            ]
        );

        $response = $this->client->send($request, [
            'query' => $parameters->all(),
        ]);

        $body = (string) $response->getBody();

        $this->logger->debug(
            LogMessageFormatter::fromAction($requestId, $action, LogMessageFormatter::TYPE_RESPONSE),
            [
                'statusCode' => $response->getStatusCode(),
                'body' => $body,
            ]
        );

        $builtResponse = HandleResponse::parse($body);

        $this->logger->debug(
            LogMessageFormatter::fromAction($requestId, $action, LogMessageFormatter::TYPE_BUILT_RESPONSE),
            [
                'head' => $builtResponse->getHead()->asXML(),
                'body' => $builtResponse->getBody()->asXML(),
            ]
        );

        $feedResponse = FeedResponseFactory::make($builtResponse->getHead());

        $this->logger->info(
            sprintf(
                '%d::%s::APIResponse::SellerCenterSdk: the images was added',
                $request->getHeaderLine('Request-ID'),
                $action
            )
        );

        return $feedResponse;
    }

    /**
     * @return mixed[]
     */
    protected function getProducts(Parameters $parameters): array
    {
        $action = 'GetProducts';

        $parameters->set(['Action' => $action]);
        $parameters->set([
            'Signature' => Signature::generate($parameters, $this->configuration->getKey())->get(),
        ]);

        $requestHeaders = $this->generateRequestHeaders();
        $requestId = $requestHeaders[self::REQUEST_ID_HEADER];
        
        $request = RequestFactory::make(
            'GET',
            $this->configuration->getEndpoint(),
            $requestHeaders
        );

        $this->logger->debug(
            LogMessageFormatter::fromAction($requestId, $action, LogMessageFormatter::TYPE_REQUEST),
            [
                'url' => (string) $request->getUri(),
                'method' => $request->getMethod(),
                'body' => (string) $request->getBody(),
                'parameters' => $parameters->all(),
            ]
        );

        $response = $this->client->send($request, [
            'query' => $parameters->all(),
        ]);

        $body = (string) $response->getBody();

        $this->logger->debug(
            LogMessageFormatter::fromAction($requestId, $action, LogMessageFormatter::TYPE_RESPONSE),
            [
                'body' => $body,
            ]
        );

        $builtResponse = HandleResponse::parse($body);

        $this->logger->debug(
            LogMessageFormatter::fromAction($requestId, $action, LogMessageFormatter::TYPE_BUILT_RESPONSE),
            [
                'head' => $builtResponse->getHead()->asXML(),
                'body' => $builtResponse->getBody()->asXML(),
            ]
        );

        $products = GlobalProductsFactory::make($builtResponse->getBody(), $this->logger);

        $productsResponse = array_values($products->all());

        $this->logger->info(
            sprintf(
                '%d::%s::APIResponse::SellerCenterSdk: %d products was recovered',
                $request->getHeaderLine('Request-ID'),
                $action,
                count($products->all())
            )
        );

        return $productsResponse;
    }

    /**
     * @return GlobalProduct[]
     */
    public function getAllProducts(int $limit = self::DEFAULT_LIMIT, int $offset = self::DEFAULT_OFFSET): array
    {
        $parameters = clone $this->parameters;

        $this->setListDimensions($parameters, $limit, $offset);

        return $this->getProducts($parameters);
    }

    /**
     * @return GlobalProduct[]
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
     * @return GlobalProduct[]
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
     * @return GlobalProduct[]
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
     * @return GlobalProduct[]
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
     * @return GlobalProduct[]
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
     * @return GlobalProduct[]
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
     * @param mixed[] $skuSellerList
     *
     * @return GlobalProduct[]
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

<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Service;

use Linio\Component\Util\Json;
use Linio\SellerCenter\Application\Security\Signature;
use Linio\SellerCenter\Factory\RequestFactory;
use Linio\SellerCenter\Factory\Xml\Category\AttributesSetFactory;
use Linio\SellerCenter\Factory\Xml\Category\CategoriesFactory;
use Linio\SellerCenter\Factory\Xml\Category\CategoryAttributesFactory;
use Linio\SellerCenter\Formatter\LogMessageFormatter;
use Linio\SellerCenter\Model\Category\AttributeSet;
use Linio\SellerCenter\Model\Category\Category;
use Linio\SellerCenter\Model\Category\CategoryAttribute;
use Linio\SellerCenter\Response\HandleResponse;

class CategoryManager extends BaseManager
{
    /**
     * @return Category[]
     */
    public function getCategoryTree(bool $debug = true): array
    {
        $action = 'GetCategoryTree';

        $parameters = clone $this->parameters;
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

        $response = $this->client->send($request, [
            'query' => $parameters->all(),
        ]);

        $body = (string) $response->getBody();

        $builtResponse = HandleResponse::parse($body);

        if ($debug) {
            $this->logger->debug(
                LogMessageFormatter::fromAction($requestId, $action, LogMessageFormatter::TYPE_REQUEST),
                [
                    'request' => [
                        'url' => (string) $request->getUri(),
                        'method' => $request->getMethod(),
                        'body' => (string) $request->getBody(),
                        'parameters' => $parameters->all(),
                    ],
                    'response' => [
                        'head' => $builtResponse->getHead()->asXML(),
                        'body' => $builtResponse->getBody()->asXML(),
                    ],
                ]
            );
        }

        HandleResponse::validate($body);

        $categories = CategoriesFactory::make($builtResponse->getBody());

        return $categories->all();
    }

    /**
     * @return CategoryAttribute[]
     */
    public function getCategoryAttributes(
        int $categoryId,
        bool $debug = true
    ): array {
        $action = 'GetCategoryAttributes';

        $parameters = clone $this->parameters;
        $parameters->set([
            'Action' => $action,
            'PrimaryCategory' => $categoryId,
        ]);
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

        $response = $this->client->send($request, [
            'query' => $parameters->all(),
        ]);

        $body = (string) $response->getBody();

        $builtResponse = HandleResponse::parse($body);

        if ($debug) {
            $this->logger->debug(
                LogMessageFormatter::fromAction($requestId, $action, LogMessageFormatter::TYPE_REQUEST),
                [
                    'request' => [
                        'url' => (string) $request->getUri(),
                        'method' => $request->getMethod(),
                        'body' => (string) $request->getBody(),
                        'parameters' => $parameters->all(),
                    ],
                    'response' => [
                        'head' => $builtResponse->getHead()->asXML(),
                        'body' => $builtResponse->getBody()->asXML(),
                    ],
                ]
            );
        }

        HandleResponse::validate($body);

        $categoryAttributes = CategoryAttributesFactory::make($builtResponse->getBody());

        return $categoryAttributes->all();
    }

    /**
     * @param mixed[]|null $attributesSetIds
     *
     * @return AttributeSet[]
     */
    public function getCategoriesByAttributesSet(
        ?array $attributesSetIds,
        bool $debug = true
    ): array {
        $action = 'GetCategoriesByAttributeSet';

        $parameters = clone $this->parameters;
        $parameters->set(['Action' => $action]);

        $attributesSetValue = 0;

        if (!empty($attributesSetIds)) {
            $attributesSetValue = Json::encode($attributesSetIds);
        }

        $parameters->set(['AttributeSet' => $attributesSetValue]);
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

        $response = $this->client->send($request, [
            'query' => $parameters->all(),
        ]);

        $body = (string) $response->getBody();

        $builtResponse = HandleResponse::parse($body);

        if ($debug) {
            $this->logger->debug(
                LogMessageFormatter::fromAction($requestId, $action, LogMessageFormatter::TYPE_REQUEST),
                [
                    'request' => [
                        'url' => (string) $request->getUri(),
                        'method' => $request->getMethod(),
                        'body' => (string) $request->getBody(),
                        'parameters' => $parameters->all(),
                    ],
                    'response' => [
                        'head' => $builtResponse->getHead()->asXML(),
                        'body' => $builtResponse->getBody()->asXML(),
                    ],
                ]
            );
        }

        HandleResponse::validate($body);

        $attributesSet = AttributesSetFactory::make($builtResponse->getBody());

        return $attributesSet->all();
    }
}

<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Service;

use Linio\Component\Util\Json;
use Linio\SellerCenter\Factory\Xml\Category\AttributesSetFactory;
use Linio\SellerCenter\Factory\Xml\Category\CategoriesFactory;
use Linio\SellerCenter\Factory\Xml\Category\CategoryAttributesFactory;

class CategoryManager extends BaseManager
{
    private const GET_CATEGORY_TREE_ACTION = 'GetCategoryTree';
    private const GET_CATEGORY_ATTRIBUTES_ACTION = 'GetCategoryAttributes';
    private const GET_CATEGORIES_BY_ATTRIBUTE_SET = 'GetCategoriesByAttributeSet';

    public function getCategoryTree(): array
    {
        $action = self::GET_CATEGORY_TREE_ACTION;

        $requestId = $this->generateRequestId();

        $builtResponse = $this->executeAction($action, $requestId);

        $categories = CategoriesFactory::make($builtResponse->getBody());

        $categoriesResponse = $categories->all();

        $this->logger->info(
            sprintf(
                '%d::%s::APIResponse::SellerCenterSdk: %d categories was recovered',
                $requestId,
                $action,
                count($categories->all())
            )
        );

        return $categoriesResponse;
    }

    public function getCategoryAttributes(int $categoryId): array
    {
        $action = self::GET_CATEGORY_ATTRIBUTES_ACTION;

        $parameters = clone $this->parameters;
        $parameters->set([
            'PrimaryCategory' => $categoryId,
        ]);

        $requestId = $this->generateRequestId();

        $builtResponse = $this->executeAction($action, $requestId, $parameters);

        $categoryAttributes = CategoryAttributesFactory::make($builtResponse->getBody());

        $categoryAttributesResponse = $categoryAttributes->all();

        $this->logger->info(
            sprintf(
                '%d::%s::APIResponse::SellerCenterSdk: %d category attributes was recovered',
                $requestId,
                $action,
                count($categoryAttributes->all())
            )
        );

        return $categoryAttributesResponse;
    }

    public function getCategoriesByAttributesSet(?array $attributesSetIds): array
    {
        $action = self::GET_CATEGORIES_BY_ATTRIBUTE_SET;

        $parameters = clone $this->parameters;

        $attributesSetValue = 0;

        if (!empty($attributesSetIds)) {
            $attributesSetValue = Json::encode($attributesSetIds);
        }

        $parameters->set(['AttributeSet' => $attributesSetValue]);

        $requestId = $this->generateRequestId();

        $builtResponse = $this->executeAction($action, $requestId);

        $attributesSet = AttributesSetFactory::make($builtResponse->getBody());

        $attributesSetResponse = $attributesSet->all();

        $this->logger->info(
            sprintf(
                '%d::%s::APIResponse::SellerCenterSdk: %d attributes set was recovered',
                $requestId,
                $action,
                count($attributesSet->all())
            )
        );

        return $attributesSetResponse;
    }
}

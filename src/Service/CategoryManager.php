<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Service;

use Linio\Component\Util\Json;
use Linio\SellerCenter\Factory\Xml\Category\AttributesSetFactory;
use Linio\SellerCenter\Factory\Xml\Category\CategoriesFactory;
use Linio\SellerCenter\Factory\Xml\Category\CategoryAttributesFactory;
use Linio\SellerCenter\Factory\Xml\Category\CategoryContentScoreRulesFactory;
use Linio\SellerCenter\Model\Category\AttributeSet;
use Linio\SellerCenter\Model\Category\Category;
use Linio\SellerCenter\Model\Category\CategoryAttribute;
use Linio\SellerCenter\Model\Category\CategoryContentScoreRule;

class CategoryManager extends BaseManager
{
    /**
     * @return Category[]
     */
    public function getCategoryTree(bool $debug = true): array
    {
        $action = 'GetCategoryTree';

        $parameters = $this->makeParametersForAction($action);

        $builtResponse = $this->executeAction(
            $action,
            $parameters,
            null,
            'GET',
            $debug
        );

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

        $parameters = $this->makeParametersForAction($action);
        $parameters->set(['PrimaryCategory' => $categoryId]);

        $builtResponse = $this->executeAction(
            $action,
            $parameters,
            null,
            'GET',
            $debug
        );

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
        $attributesSetValue = 0;

        $parameters = $this->makeParametersForAction($action);

        if (!empty($attributesSetIds)) {
            $attributesSetValue = Json::encode($attributesSetIds);
        }

        $parameters->set(['AttributeSet' => $attributesSetValue]);

        $builtResponse = $this->executeAction(
            $action,
            $parameters,
            null,
            'GET',
            $debug
        );

        $attributesSet = AttributesSetFactory::make($builtResponse->getBody());

        return $attributesSet->all();
    }

    /**
     * @return CategoryContentScoreRule[]
     */
    public function getCategoryContentScoreRules(
        int $categoryId,
        bool $debug = true
    ): array {
        $action = 'GetContentScore';

        $parameters = $this->makeParametersForAction($action);
        $parameters->set(['CategoryId' => $categoryId]);

        $builtResponse = $this->executeAction(
            $action,
            $parameters,
            null,
            'GET',
            $debug
        );

        $categoryContentScoreRules = CategoryContentScoreRulesFactory::make($builtResponse->getBody());

        return $categoryContentScoreRules->all();
    }
}

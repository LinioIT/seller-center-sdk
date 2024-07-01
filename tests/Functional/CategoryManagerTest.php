<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Functional;

use Linio\SellerCenter\ClientHelper;
use Linio\SellerCenter\LinioTestCase;
use Linio\SellerCenter\Model\Category\Category;
use Linio\SellerCenter\Model\Category\CategoryContentScoreRule;
use Linio\SellerCenter\Model\Category\CategoryContentScoreRules;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Log\LoggerInterface;

class CategoryManagerTest extends LinioTestCase
{
    use ClientHelper;

    /**
     * @var ObjectProphecy
     */
    protected $logger;

    public function prepareLogTest(bool $debug): void
    {
        $this->logger = $this->prophesize(LoggerInterface::class);

        $this->logger->debug(
            Argument::type('string'),
            Argument::type('array')
        )->shouldBeCalled();

        if (!$debug) {
            $this->logger->debug(
                Argument::type('string'),
                Argument::type('array')
            )->shouldNotBeCalled();
        }
    }

    public function testTheCategoriesWillBeCreatedFromAnXml(): void
    {
        $body = $this->getSchema('Category/GetCategoryTreeSucessResponse.xml');
        $sdk = $this->getSdkClient($body);

        $result = $sdk->categories()->getCategoryTree();

        $this->assertIsArray($result);
        $this->assertContainsOnlyInstancesOf(Category::class, $result);

        $category = current($result);

        $this->assertEquals('Quadrilaterals', $category->getName());
        $this->assertEquals(2790, $category->getId());
        $this->assertEquals('QUADRILATER', $category->getGlobalIdentifier());
        $this->assertEquals(1, $category->getAttributeSetId());

        $child_1 = current($category->getChildren());

        $this->assertEquals('Rectangles', $child_1->getName());
        $this->assertEquals(589, $child_1->getId());
        $this->assertEquals('RECTANGLE', $child_1->getGlobalIdentifier());
        $this->assertEquals(1, $child_1->getAttributeSetId());

        $child_2 = current($child_1->getChildren());

        $this->assertEquals('Squares', $child_2->getName());
        $this->assertEquals(603, $child_2->getId());
        $this->assertEquals('SQUARE', $child_2->getGlobalIdentifier());
        $this->assertEquals(2, $child_2->getAttributeSetId());
    }

    public function testTheCategoryContentScoreRulesWillBeCreatedFromAnXml(): void
    {
        $body = $this->getSchema('Category/GetCategoryContentScoreRulesSuccessResponse.xml');
        $sdk = $this->getSdkClient($body);

        $categoryId = 1;

        $result = $sdk->categories()->getCategoryContentScoreRules($categoryId);

        $this->assertInstanceOf(CategoryContentScoreRules::class, $result);
        $this->assertContainsOnlyInstancesOf(CategoryContentScoreRule::class, $result->all());

        $rules = ['CHARACTER_COUNT', 'WORD_COUNT'];
        $fields = ['title', 'description'];
        $scores = [44, 44];
        $mins = [20, 50];
        $maxs = [60, null];

        for ($i = 0; $i < 2; $i++) {
            $categoryContentScoreRule = $result->all()[$i];

            $this->assertEquals($rules[$i], $categoryContentScoreRule->getRule());
            $this->assertEquals($fields[$i], $categoryContentScoreRule->getField());
            $this->assertEquals($scores[$i], $categoryContentScoreRule->getScore());

            $config = $categoryContentScoreRule->getConfig();

            $this->assertEquals($mins[$i], $config->getMin());
            $this->assertEquals($maxs[$i], $config->getMax());
        }
    }

    /**
     * @dataProvider debugParameter
     */
    public function testItLogsDependingOnDebugParamWhenGetCategoryTreeSuccessResponse(bool $debug): void
    {
        $body = $this->getSchema('Category/GetCategoryTreeSucessResponse.xml');
        $this->prepareLogTest($debug);
        $sdkClient = $this->getSdkClient($body, $this->logger);

        $sdkClient->categories()->getCategoryTree($debug);
    }

    /**
     * @dataProvider debugParameter
     */
    public function testItLogsDependingOnDebugParamWhenGetCategoryAttributesTreeSuccessResponse(bool $debug): void
    {
        $body = $this->getSchema('Category/GetCategoryAttributesSuccessResponse.xml');
        $this->prepareLogTest($debug);
        $sdkClient = $this->getSdkClient($body, $this->logger);

        $sdkClient->categories()->getCategoryAttributes(
            1,
            $debug
        );
    }

    /**
     * @dataProvider debugParameter
     */
    public function testItLogsDependingOnDebugParamWhenGetCategoriesByAttributeSetSuccessResponse(bool $debug): void
    {
        $body = $this->getSchema('Category/GetCategoriesByAttributesSetSuccessResponse.xml');
        $this->prepareLogTest($debug);
        $sdkClient = $this->getSdkClient($body, $this->logger);

        $sdkClient->categories()->getCategoriesByAttributesSet(
            [1],
            $debug
        );
    }

    public function debugParameter()
    {
        return [
            [false],
            [true],
        ];
    }
}

<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Functional;

use Linio\SellerCenter\ClientHelper;
use Linio\SellerCenter\LinioTestCase;
use Linio\SellerCenter\Model\Category\Category;
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

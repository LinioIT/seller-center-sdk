<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Helper;

use Linio\SellerCenter\LinioTestCase;
use Linio\SellerCenter\Model\Category\CategoryAttribute;
use Linio\SellerCenter\Model\Category\CategoryAttributeOptions;

final class CategoryAttributesHelperTest extends LinioTestCase
{
    public function testItReturnsEmptyArrayWhenNoVariationAttributes(): void
    {
        $attributes = [
            $this->createCategoryAttribute('Size', 'Global', true),
            $this->createCategoryAttribute('Color', 'Global', true),
        ];

        $result = CategoryAttributesHelper::getCategoryAttributesFeedNames($attributes);

        $this->assertEmpty($result);
    }

    public function testItReturnsFeedNamesForVariationAttributes(): void
    {
        $attributes = [
            $this->createCategoryAttribute('Color', 'Variation', false),
            $this->createCategoryAttribute('Size', 'Variation', false),
        ];

        $result = CategoryAttributesHelper::getCategoryAttributesFeedNames($attributes);

        $this->assertEquals(['Color', 'Size'], $result);
    }

    public function testItSkipsGlobalVariationAttributes(): void
    {
        $attributes = [
            $this->createCategoryAttribute('Color', 'Variation', true),
            $this->createCategoryAttribute('Size', 'Variation', false),
        ];

        $result = CategoryAttributesHelper::getCategoryAttributesFeedNames($attributes);

        $this->assertEquals(['Size'], $result);
    }

    private function createCategoryAttribute(string $feedName, string $groupName, bool $isGlobalAttribute): CategoryAttribute
    {
        return new CategoryAttribute(
            'Color',
            $feedName,
            'Color',
            '123',
            'system',
            new CategoryAttributeOptions(),
            true,
            $isGlobalAttribute,
            null,
            null,
            null,
            $groupName
        );
    }
}

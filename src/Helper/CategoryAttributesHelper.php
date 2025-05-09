<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Helper;

use Linio\SellerCenter\Model\Category\CategoryAttribute;

final class CategoryAttributesHelper
{
    private const VARIATION_STRING = 'Variation';

    /**
     * @param CategoryAttribute[] $categoryAttributes
     *
     * @return string[]
     */
    public static function getCategoryAttributesFeedNames(array $categoryAttributes): array
    {
        $variationAttributesNames = [];
        foreach ($categoryAttributes as $attribute) {
            if (self::isVariationAttribute($attribute)) {
                $variationAttributesNames[] = $attribute->getFeedName();
            }
        }

        return $variationAttributesNames;
    }

    private static function isVariationAttribute(CategoryAttribute $categoryAttribute): bool
    {
        return $categoryAttribute->getGroupName() === self::VARIATION_STRING
                && !$categoryAttribute->isGlobalAttribute()
                    && $categoryAttribute->getFeedName() !== self::VARIATION_STRING;
    }
}

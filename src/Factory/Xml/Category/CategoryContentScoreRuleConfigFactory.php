<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Xml\Category;

use Linio\SellerCenter\Model\Category\CategoryContentScoreRuleConfig;

class CategoryContentScoreRuleConfigFactory
{
    public static function make(\SimpleXMLElement $element): CategoryContentScoreRuleConfig
    {
        return new CategoryContentScoreRuleConfig(
            !empty($element->min) ? (int) $element->min : null,
            !empty($element->max) ? (int) $element->max : null
        );
    }
}

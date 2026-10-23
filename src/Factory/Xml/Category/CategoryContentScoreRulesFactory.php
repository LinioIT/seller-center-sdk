<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Xml\Category;

use Linio\SellerCenter\Exception\InvalidXmlStructureException;
use Linio\SellerCenter\Model\Category\CategoryContentScoreRules;

class CategoryContentScoreRulesFactory
{
    public static function make(\SimpleXMLElement $element): CategoryContentScoreRules
    {
        if (empty($element->CategoryRules->Config)) {
            throw new InvalidXmlStructureException('CategoryContentScoreRules', 'Config');
        }

        $categoryContentScoreRules = new CategoryContentScoreRules();

        foreach ($element->CategoryRules->Config as $item) {
            if (!empty($item)) {
                $categoryContentScoreRule = CategoryContentScoreRuleFactory::make($item);
                $categoryContentScoreRules->add($categoryContentScoreRule);
            }
        }

        return $categoryContentScoreRules;
    }
}

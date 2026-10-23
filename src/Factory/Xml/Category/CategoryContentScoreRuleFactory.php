<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Xml\Category;

use Linio\SellerCenter\Exception\InvalidXmlStructureException;
use Linio\SellerCenter\Model\Category\CategoryContentScoreRule;

class CategoryContentScoreRuleFactory
{
    public static function make(\SimpleXMLElement $element): CategoryContentScoreRule
    {
        if (!property_exists($element, 'Rule')) {
            throw new InvalidXmlStructureException('CategoryContentScoreRule', 'Rule');
        }

        if (!property_exists($element, 'Field')) {
            throw new InvalidXmlStructureException('CategoryContentScoreRule', 'Field');
        }

        if (!property_exists($element, 'Score')) {
            throw new InvalidXmlStructureException('CategoryContentScoreRule', 'Score');
        }

        if (!property_exists($element, 'Config')) {
            throw new InvalidXmlStructureException('CategoryContentScoreRule', 'Config');
        }

        $config = CategoryContentScoreRuleConfigFactory::make($element->Config);

        return new CategoryContentScoreRule(
            (string) $element->Rule,
            (string) $element->Field,
            (int) $element->Score,
            $config
        );
    }
}

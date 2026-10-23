<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Category;

use Linio\SellerCenter\Factory\Xml\Category\CategoryContentScoreRuleConfigFactory;
use Linio\SellerCenter\LinioTestCase;
use Linio\SellerCenter\Model\Category\CategoryContentScoreRuleConfig;

class CategoryContentScoreRuleConfigTest extends LinioTestCase
{
    public function testItReturnsCategoryContentScoreRuleConfigObject(): void
    {
        $success = $this->getSchema('Category/GetCategoryContentScoreRulesSuccessResponse.xml');

        $xml = simplexml_load_string($success);
        $categoryContentScoreRuleConfig = CategoryContentScoreRuleConfigFactory::make($xml->Body->CategoryRules->Config->Config);

        $this->assertNotEmpty($categoryContentScoreRuleConfig);
        $this->assertInstanceOf(CategoryContentScoreRuleConfig::class, $categoryContentScoreRuleConfig);
    }
}

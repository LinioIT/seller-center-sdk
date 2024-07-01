<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Category;

use Linio\SellerCenter\Exception\InvalidXmlStructureException;
use Linio\SellerCenter\Factory\Xml\Category\CategoryContentScoreRuleFactory;
use Linio\SellerCenter\LinioTestCase;
use Linio\SellerCenter\Model\Category\CategoryContentScoreRule;

class CategoryContentScoreRuleTest extends LinioTestCase
{
    public function testItReturnsCategoryContentScoreRuleObject(): void
    {
        $success = $this->getSchema('Category/GetCategoryContentScoreRulesSuccessResponse.xml');

        $xml = simplexml_load_string($success);
        $categoryContentScoreRule = CategoryContentScoreRuleFactory::make($xml->Body->CategoryRules->Config);

        $this->assertNotEmpty($categoryContentScoreRule);
        $this->assertInstanceOf(CategoryContentScoreRule::class, $categoryContentScoreRule);

        $json = '{"rule":"CHARACTER_COUNT","field":"title","score":44,"config":{"min":20,"max":60}}';
        $this->assertEquals(json_encode($categoryContentScoreRule->jsonSerialize()), $json);
    }

    public function testItThrowsAnExceptionWithAnNonExistentRuleTag(): void
    {
        $this->expectException(InvalidXmlStructureException::class);
        $this->expectExceptionMessage('The xml structure is not valid for a CategoryContentScoreRule. The property Rule should exist.');

        $success = '<Config>
                  <Field>title</Field>
                  <Score>44</Score>
                  <Config>
                    <max>60</max>
                    <min>20</min>
                  </Config>
			          </Config>';

        $xml = simplexml_load_string($success);
        CategoryContentScoreRuleFactory::make($xml);
    }

    public function testItThrowsAnExceptionWithAnNonExistentFieldTag(): void
    {
        $this->expectException(InvalidXmlStructureException::class);
        $this->expectExceptionMessage('The xml structure is not valid for a CategoryContentScoreRule. The property Field should exist.');

        $success = '<Config>
                  <Rule>CHARACTER_COUNT</Rule>
                  <Score>44</Score>
                  <Config>
                    <max>60</max>
                    <min>20</min>
                  </Config>
			          </Config>';

        $xml = simplexml_load_string($success);
        CategoryContentScoreRuleFactory::make($xml);
    }

    public function testItThrowsAnExceptionWithAnNonExistentScoreTag(): void
    {
        $this->expectException(InvalidXmlStructureException::class);
        $this->expectExceptionMessage('The xml structure is not valid for a CategoryContentScoreRule. The property Score should exist.');

        $success = '<Config>
                  <Rule>CHARACTER_COUNT</Rule>
                  <Field>title</Field>
                  <Config>
                    <max>60</max>
                    <min>20</min>
                  </Config>
			          </Config>';

        $xml = simplexml_load_string($success);
        CategoryContentScoreRuleFactory::make($xml);
    }

    public function testItThrowsAnExceptionWithAnNonExistentConfigTag(): void
    {
        $this->expectException(InvalidXmlStructureException::class);
        $this->expectExceptionMessage('The xml structure is not valid for a CategoryContentScoreRule. The property Config should exist.');

        $success = '<Config>
                  <Rule>CHARACTER_COUNT</Rule>
                  <Field>title</Field>
                  <Score>44</Score>
			          </Config>';

        $xml = simplexml_load_string($success);
        CategoryContentScoreRuleFactory::make($xml);
    }
}

<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Category;

use Linio\SellerCenter\Exception\InvalidXmlStructureException;
use Linio\SellerCenter\Factory\Xml\Category\CategoryContentScoreRulesFactory;
use Linio\SellerCenter\LinioTestCase;
use Linio\SellerCenter\Model\Category\CategoryContentScoreRule;
use Linio\SellerCenter\Model\Category\CategoryContentScoreRules;

class CategoryContentScoreRulesTest extends LinioTestCase
{
    public function testItReturnsCategoryContentScoreRulesObject(): void
    {
        $success = $this->getSchema('Category/GetCategoryContentScoreRulesSuccessResponse.xml');

        $xml = simplexml_load_string($success);
        $categoryContentScoreRules = CategoryContentScoreRulesFactory::make($xml->Body);

        $this->assertNotEmpty($categoryContentScoreRules);
        $this->assertInstanceOf(CategoryContentScoreRules::class, $categoryContentScoreRules);
        $this->assertContainsOnlyInstancesOf(CategoryContentScoreRule::class, $categoryContentScoreRules->all());
    }

    public function testItThrowsAnExceptionWithAnNonExistentConfigTag(): void
    {
        $this->expectException(InvalidXmlStructureException::class);
        $this->expectExceptionMessage('The xml structure is not valid for a CategoryContentScoreRules. The property Config should exist.');

        $success = '<?xml version="1.0" encoding="UTF-8"?>
                <SuccessResponse>
                  <Head>
                    <RequestId>G010302</RequestId>
                    <RequestAction>getContentScore</RequestAction>
                    <ResponseType>Content Score</ResponseType>
                    <Timestamp>2024-06-03T22:13:37-0400</Timestamp>
                  </Head>
                  <Body>
                    <CategoryRules>
                    </CategoryRules>
                  </Body>
                </SuccessResponse>';

        $xml = simplexml_load_string($success);
        CategoryContentScoreRulesFactory::make($xml);
    }
}

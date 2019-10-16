<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Functional;

use Linio\SellerCenter\Application\Configuration;
use Linio\SellerCenter\ClientHelper;
use Linio\SellerCenter\LinioTestCase;
use Linio\SellerCenter\Model\Category\Category;
use Linio\SellerCenter\SellerCenterSdk;

class CategoryManagerTest extends LinioTestCase
{
    use ClientHelper;

    public function testTheCategoriesWillBeCreatedFromAnXml(): void
    {
        $body = '<?xml version="1.0" encoding="UTF-8"?>
                <SuccessResponse>
                  <Head>
                    <RequestId/>
                    <RequestAction>GetCategoryTree</RequestAction>
                    <ResponseType>Categories</ResponseType>
                    <Timestamp>2015-07-01T11:11:11+0000</Timestamp>
                  </Head>
                  <Body>
                    <Categories>
                      <Category>
                        <Name>Quadrilaterals</Name>
                        <CategoryId>2790</CategoryId>
                        <GlobalIdentifier>QUADRILATER</GlobalIdentifier>
                        <AttributeSetId>1</AttributeSetId>
                        <Children>
                          <Category>
                            <Name>Rectangles</Name>
                            <CategoryId>589</CategoryId>
                            <GlobalIdentifier>RECTANGLE</GlobalIdentifier>
                            <AttributeSetId>1</AttributeSetId>
                            <Children>
                              <Category>
                                <Name>Squares</Name>
                                <CategoryId>603</CategoryId>
                                <GlobalIdentifier>SQUARE</GlobalIdentifier>
                                <AttributeSetId>2</AttributeSetId>
                                <Children/>
                              </Category>
                            </Children>
                          </Category>
                        </Children>
                      </Category>
                    </Categories>
                  </Body>
                </SuccessResponse>';

        $client = $this->createClientWithResponse($body);

        $env = $this->getParameters();
        $configuration = new Configuration($env['key'], $env['username'], $env['endpoint'], $env['version']);

        $sdk = new SellerCenterSdk($configuration, $client);
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
}

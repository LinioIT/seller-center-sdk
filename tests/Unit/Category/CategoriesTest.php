<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Unit;

use Linio\SellerCenter\Exception\InvalidXmlStructureException;
use Linio\SellerCenter\Factory\Xml\Category\CategoriesFactory;
use Linio\SellerCenter\LinioTestCase;
use Linio\SellerCenter\Model\Category\Categories;
use Linio\SellerCenter\Model\Category\Category;
use SimpleXMLElement;

class CategoriesTest extends LinioTestCase
{
    public function testItReturnsAnEmptyArrayFromAEmptyCollection(): void
    {
        $categories = new Categories();
        $this->assertIsArray($categories->all());
    }

    public function testItReturnsACollectionOfCategories(): void
    {
        $success = '<?xml version="1.0" encoding="UTF-8"?>
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

        $xml = simplexml_load_string($success);
        $categories = CategoriesFactory::make($xml->Body);

        $result = $categories->all();
        $this->assertNotEmpty($result);
        $this->assertContainsOnlyInstancesOf(Category::class, $result);
    }

    public function testThrowsAnExceptionWithAInvalidXml(): void
    {
        $this->expectException(InvalidXmlStructureException::class);
        $this->expectExceptionMessage('The xml structure is not valid for a Categories. The property Category should exist.');

        CategoriesFactory::make(new SimpleXMLElement('<xml></xml>'));
    }

    public function testItLoadsACategoryWithoutChildren(): void
    {
        $success = '<?xml version="1.0" encoding="UTF-8"?>
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
                          </Category>
                        </Categories>
                      </Body>
                    </SuccessResponse>';

        $xml = simplexml_load_string($success);
        $categories = CategoriesFactory::make($xml->Body);

        $result = $categories->all();

        $this->assertEquals($result[0]->getName(), 'Quadrilaterals');
        $this->assertEquals($result[0]->getId(), '2790');
        $this->assertEquals($result[0]->getGlobalIdentifier(), 'QUADRILATER');
        $this->assertEquals($result[0]->getAttributeSetId(), 1);
        $this->assertCount(1, $result);
    }

    public function testItLoadsACategoryWithOneChild(): void
    {
        $success = '<?xml version="1.0" encoding="UTF-8"?>
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
                              </Category>
                            </Children>
                          </Category>
                        </Categories>
                      </Body>
                    </SuccessResponse>';

        $xml = simplexml_load_string($success);
        $categories = CategoriesFactory::make($xml->Body);

        $result = $categories->all();

        $this->assertEquals('Quadrilaterals', $result[0]->getName());
        $this->assertEquals('2790', $result[0]->getId());
        $this->assertEquals('QUADRILATER', $result[0]->getGlobalIdentifier());
        $this->assertEquals(1, $result[0]->getAttributeSetId());
        $this->assertCount(1, $result);

        $this->assertEquals('Rectangles', $result[0]->getChildren()[0]->getName());
        $this->assertEquals('589', $result[0]->getChildren()[0]->getId());
        $this->assertEquals('RECTANGLE', $result[0]->getChildren()[0]->getGlobalIdentifier());
        $this->assertEquals($result[0]->getChildren()[0]->getAttributeSetId(), 1);
    }

    public function testItLoadsACategoryWithTwoChildrenAtTheSameLevel(): void
    {
        $success = '<?xml version="1.0" encoding="UTF-8"?>
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
                              </Category>
                              <Category>
                                <Name>Rhomboids</Name>
                                <CategoryId>590</CategoryId>
                                <GlobalIdentifier>RHOMBOIDS</GlobalIdentifier>
                                <AttributeSetId>1</AttributeSetId>
                              </Category>
                            </Children>
                          </Category>
                        </Categories>
                      </Body>
                    </SuccessResponse>';

        $xml = simplexml_load_string($success);
        $categories = CategoriesFactory::make($xml->Body);

        $result = $categories->all();

        $this->assertEquals('Quadrilaterals', $result[0]->getName());
        $this->assertEquals('2790', $result[0]->getId());
        $this->assertEquals('QUADRILATER', $result[0]->getGlobalIdentifier());
        $this->assertEquals(1, $result[0]->getAttributeSetId());
        $this->assertCount(1, $result);

        $this->assertEquals('Rectangles', $result[0]->getChildren()[0]->getName());
        $this->assertEquals('589', $result[0]->getChildren()[0]->getId());
        $this->assertEquals('RECTANGLE', $result[0]->getChildren()[0]->getGlobalIdentifier());
        $this->assertEquals(1, $result[0]->getChildren()[0]->getAttributeSetId());
    }

    public function testItLoadsACategoryWithManyLevelsOfChildren(): void
    {
        $success = '<?xml version="1.0" encoding="UTF-8"?>
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

        $xml = simplexml_load_string($success);
        $categories = CategoriesFactory::make($xml->Body);

        $result = $categories->all();

        $this->assertEquals('Quadrilaterals', $result[0]->getName());
        $this->assertEquals('2790', $result[0]->getId());
        $this->assertEquals('QUADRILATER', $result[0]->getGlobalIdentifier());
        $this->assertEquals(1, $result[0]->getAttributeSetId());

        $this->assertEquals('Rectangles', $result[0]->getChildren()[0]->getName());
        $this->assertEquals('589', $result[0]->getChildren()[0]->getId());
        $this->assertEquals('RECTANGLE', $result[0]->getChildren()[0]->getGlobalIdentifier());
        $this->assertEquals(1, $result[0]->getChildren()[0]->getAttributeSetId());

        $this->assertEquals('Squares', $result[0]->getChildren()[0]->getChildren()[0]->getName());
        $this->assertEquals('603', $result[0]->getChildren()[0]->getChildren()[0]->getId());
        $this->assertEquals('SQUARE', $result[0]->getChildren()[0]->getChildren()[0]->getGlobalIdentifier());
        $this->assertEquals($result[0]->getChildren()[0]->getChildren()[0]->getAttributeSetId(), 2);
    }
}

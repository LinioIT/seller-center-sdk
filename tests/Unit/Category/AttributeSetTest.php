<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Category;

use Linio\Component\Util\Json;
use Linio\SellerCenter\Exception\InvalidXmlStructureException;
use Linio\SellerCenter\Factory\Xml\Category\AttributeSetFactory;
use Linio\SellerCenter\Factory\Xml\Category\AttributesSetFactory;
use Linio\SellerCenter\LinioTestCase;
use Linio\SellerCenter\Model\Category\AttributeSet;
use Linio\SellerCenter\Model\Category\Categories;
use Linio\SellerCenter\Model\Category\Category;

class AttributeSetTest extends LinioTestCase
{
    public function testItReturnsAnAttributeSetsObject(): void
    {
        $success = '<?xml version="1.0" encoding="UTF-8"?>
                    <SuccessResponse>
                      <Head>
                        <RequestId/>
                        <RequestAction>GetCategoriesByAttributeSet</RequestAction>
                        <ResponseType>AttributeSets</ResponseType>
                        <Timestamp>2015-07-16T05:19:15+0200</Timestamp>
                      </Head>
                      <Body>
                        <AttributeSets>
                          <AttributeSet>
                            <AttributeSetId>3</AttributeSetId>
                            <Name>home_living</Name>
                            <GlobalIdentifier>HL</GlobalIdentifier>
                            <Categories>
                              <Category>
                                <Name>Home &amp; Living</Name>
                                <CategoryId>390</CategoryId>
                                <GlobalIdentifier/>
                                <Children>
                                  <Category>
                                    <Name>Large Appliances</Name>
                                    <CategoryId>2931</CategoryId>
                                    <GlobalIdentifier/>
                                    <Children>
                                      <Category>
                                        <Name>Fridge &amp; Freezers</Name>
                                        <CategoryId>2949</CategoryId>
                                        <GlobalIdentifier/>
                                        <Children/>
                                      </Category>
                                      <Category>
                                        <Name>Washing Machine</Name>
                                        <CategoryId>2948</CategoryId>
                                        <GlobalIdentifier/>
                                        <Children/>
                                      </Category>
                                      <Category>
                                        <Name>Microwave</Name>
                                        <CategoryId>2947</CategoryId>
                                        <GlobalIdentifier/>
                                        <Children/>
                                      </Category>
                                    </Children>
                                  </Category>
                                </Children>
                              </Category>
                            </Categories>
                          </AttributeSet>
                        </AttributeSets>
                      </Body>
                    </SuccessResponse>';

        $xml = simplexml_load_string($success);
        $attributeSets = AttributesSetFactory::make($xml->Body);

        $result = $attributeSets->all();
        $this->assertNotEmpty($result);
        $this->assertContainsOnlyInstancesOf(AttributeSet::class, $result);

        $attributeSet = $result[0];
        $this->assertEquals(3, $attributeSet->getAttributeSetId());
        $this->assertEquals('home_living', $attributeSet->getName());
        $this->assertEquals('HL', $attributeSet->getGlobalIdentifier());

        $categories = $attributeSet->getCategories();
        $this->assertInstanceOf(Categories::class, $categories);
        $this->assertCount(1, $categories->all());

        $children = $categories->all()[0]->getChildren();

        $this->assertContainsOnlyInstancesOf(Category::class, $children);

        $this->assertEquals('Large Appliances', $children[0]->getName());
        $this->assertEquals('2931', $children[0]->getId());
        $this->assertEquals('', $children[0]->getGlobalIdentifier());
    }

    public function testItReturnsAnAttributeSetsObjectWithEmptyCategories(): void
    {
        $success = '<?xml version="1.0" encoding="UTF-8"?>
                    <SuccessResponse>
                      <Head>
                        <RequestId/>
                        <RequestAction>GetCategoriesByAttributeSet</RequestAction>
                        <ResponseType>AttributeSets</ResponseType>
                        <Timestamp>2015-07-16T05:19:15+0200</Timestamp>
                      </Head>
                      <Body>
                        <AttributeSets>
                          <AttributeSet>
                            <AttributeSetId>3</AttributeSetId>
                            <Name>home_living</Name>
                            <GlobalIdentifier>HL</GlobalIdentifier>
                            <Categories/>
                          </AttributeSet>
                        </AttributeSets>
                      </Body>
                    </SuccessResponse>';

        $xml = simplexml_load_string($success);
        $attributeSets = AttributesSetFactory::make($xml->Body);

        $result = $attributeSets->all();
        $this->assertNotEmpty($result);
        $this->assertContainsOnlyInstancesOf(AttributeSet::class, $result);

        $attributeSet = $result[0];
        $this->assertEquals(3, $attributeSet->getAttributeSetId());
        $this->assertEquals('home_living', $attributeSet->getName());
        $this->assertEquals('HL', $attributeSet->getGlobalIdentifier());

        $categories = $attributeSet->getCategories();
        $this->assertInstanceOf(Categories::class, $categories);
        $this->assertCount(0, $categories->all());
    }

    public function testItThrowsAnExceptionWithAnNonExistentAttributeSetId(): void
    {
        $this->expectException(InvalidXmlStructureException::class);
        $this->expectExceptionMessage('The xml structure is not valid for a AttributeSet. The property AttributeSetId should exist.');

        $success = '<AttributeSet>
                        <Name>home_living</Name>
                        <GlobalIdentifier>HL</GlobalIdentifier>
                        <Categories>
                          <Category>
                            <Name>Home &amp; Living</Name>
                            <CategoryId>390</CategoryId>
                            <GlobalIdentifier/>
                            <Children/>
                          </Category>
                        </Categories>
                      </AttributeSet>';

        $xml = simplexml_load_string($success);
        $attributeSets = AttributeSetFactory::make($xml);

        $result = $$attributeSets->all();
        $this->assertNotEmpty($result);
        $this->assertContainsOnlyInstancesOf(AttributeSet::class, $result);
    }

    public function testItThrowsAnExceptionWithAnNonExistentNameTag(): void
    {
        $this->expectException(InvalidXmlStructureException::class);
        $this->expectExceptionMessage('The xml structure is not valid for a AttributeSet. The property Name should exist.');

        $success = '<AttributeSet>
                        <AttributeSetId>3</AttributeSetId>
                        <GlobalIdentifier>HL</GlobalIdentifier>
                        <Categories>
                          <Category>
                            <Name>Home &amp; Living</Name>
                            <CategoryId>390</CategoryId>
                            <GlobalIdentifier/>
                            <Children/>
                          </Category>
                        </Categories>
                      </AttributeSet>';

        $xml = simplexml_load_string($success);
        AttributeSetFactory::make($xml);
    }

    public function testItThrowsAnExceptionWithAnNonExistentGlobalIdentifierTag(): void
    {
        $this->expectException(InvalidXmlStructureException::class);
        $this->expectExceptionMessage('The xml structure is not valid for a AttributeSet. The property GlobalIdentifier should exist.');

        $success = '<AttributeSet>
                        <AttributeSetId>3</AttributeSetId>
                        <Name>home_living</Name>
                        <Categories>
                          <Category>
                            <Name>Home &amp; Living</Name>
                            <CategoryId>390</CategoryId>
                            <GlobalIdentifier/>
                            <Children/>
                          </Category>
                        </Categories>
                      </AttributeSet>';

        $xml = simplexml_load_string($success);
        AttributeSetFactory::make($xml);
    }

    public function testItThrowsAnExceptionWithAnNonExistentCategoriesTag(): void
    {
        $this->expectException(InvalidXmlStructureException::class);
        $this->expectExceptionMessage('The xml structure is not valid for a AttributeSet. The property Categories should exist.');

        $success = '<AttributeSet>
                        <AttributeSetId>3</AttributeSetId>
                        <Name>home_living</Name>
                        <GlobalIdentifier>HL</GlobalIdentifier>
                      </AttributeSet>';

        $xml = simplexml_load_string($success);
        AttributeSetFactory::make($xml);
    }

    public function testItReturnsAJsonRepresentation(): void
    {
        $id = 589;
        $attributeName = 'Rectangles';
        $globalIdentifier = 1;
        $parentName = 'RectanglesGlobal';
        $parentId = 1;
        $childName = 'Triangles';
        $childId = 2;

        $simpleXml = simplexml_load_string(sprintf('
                          <AttributeSet>
                            <AttributeSetId>%s</AttributeSetId>
                            <Name>%s</Name>
                            <GlobalIdentifier>%s</GlobalIdentifier>
                            <Categories>
                              <Category>
                                <Name>%s</Name>
                                <CategoryId>%s</CategoryId>
                                <GlobalIdentifier/>
                                <Children>
                                  <Category>
                                    <Name>%s</Name>
                                    <CategoryId>%s</CategoryId>
                                    <GlobalIdentifier/>
                                    <Children/>
                                  </Category>
                                </Children>
                              </Category>
                            </Categories>
                          </AttributeSet>', $id, $attributeName, $globalIdentifier, $parentName, $parentId, $childName, $childId));

        $attributeSet = AttributeSetFactory::make($simpleXml);

        $expectedJson = sprintf('{"attributeSetId":%d,"name":"%s","globalIdentifier":"%s","categories":[{"categoryId":%d,"name":"%s","globalIdentifier":"","attributeSetId":null,"children":[{"categoryId":%d,"name":"%s","globalIdentifier":"","attributeSetId":null,"children":[],"attributes":[]}],"attributes":[]}]}', $id, $attributeName, $globalIdentifier, $parentId, $parentName, $childId, $childName);
        $this->assertJsonStringEqualsJsonString($expectedJson, Json::encode($attributeSet));
    }
}

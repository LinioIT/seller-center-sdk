<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Unit\Category;

use Linio\Component\Util\Json;
use Linio\SellerCenter\Exception\InvalidXmlStructureException;
use Linio\SellerCenter\Factory\Xml\Category\CategoryFactory;
use Linio\SellerCenter\LinioTestCase;
use Linio\SellerCenter\Model\Category\Category;
use SimpleXMLElement;

class CategoryTest extends LinioTestCase
{
    public function testItCreatesACategoryWithoutChildren(): void
    {
        $id = 1;
        $name = 'name';
        $globalIdentifier = 'global-identifier';
        $setId = 2;

        $category = Category::build($id, $name, $globalIdentifier, $setId);

        $this->assertEquals($id, $category->getId());
        $this->assertEquals($name, $category->getName());
        $this->assertEquals($globalIdentifier, $category->getGlobalIdentifier());
        $this->assertEquals($setId, $category->getAttributeSetId());
        $this->assertEmpty($category->getChildren());
        $this->assertIsArray($category->getChildren());
    }

    public function testItCreatesACategoryWithOneChildren(): void
    {
        $child_1 = $this->createMock(Category::class);
        $child_2 = $this->createMock(Category::class);
        $children = [$child_1, $child_2];

        $id = 1;
        $name = 'name';
        $globalIdentifier = 'global-identifier';
        $setId = 2;

        $category = Category::build($id, $name, $globalIdentifier, $setId, $children);

        $this->assertEquals($id, $category->getId());
        $this->assertEquals($name, $category->getName());
        $this->assertEquals($globalIdentifier, $category->getGlobalIdentifier());
        $this->assertEquals($setId, $category->getAttributeSetId());
        $this->assertNotEmpty($category->getChildren());
        $this->assertContainsOnlyInstancesOf(Category::class, $category->getChildren());
    }

    public function testItCreatesACategoryWithId(): void
    {
        $id = 1;

        $category = Category::fromId($id);

        $this->assertInstanceOf(Category::class, $category);
        $this->assertEquals($id, $category->getId());
    }

    public function testThrowsAExceptionWithoutACategoryIdInTheXml(): void
    {
        $this->expectException(InvalidXmlStructureException::class);

        $this->expectExceptionMessage('The xml structure is not valid for a Category. The property CategoryId should exist.');

        $xml = '<Category>
                    <Name>Rectangles</Name>
                    <GlobalIdentifier>RECTANGLE</GlobalIdentifier>
                    <AttributeSetId>1</AttributeSetId>
                </Category>';

        CategoryFactory::make(new SimpleXMLElement($xml));
    }

    public function testThrowsAExceptionWithoutANameInTheXml(): void
    {
        $this->expectException(InvalidXmlStructureException::class);

        $this->expectExceptionMessage('The xml structure is not valid for a Category. The property Name should exist.');

        $xml = '<Category>
                    <CategoryId>589</CategoryId>
                    <GlobalIdentifier>RECTANGLE</GlobalIdentifier>
                    <AttributeSetId>1</AttributeSetId>
                </Category>';

        CategoryFactory::make(new SimpleXMLElement($xml));
    }

    public function testThrowsAExceptionWithoutAGlobalIdentifierInTheXml(): void
    {
        $this->expectException(InvalidXmlStructureException::class);

        $this->expectExceptionMessage('The xml structure is not valid for a Category. The property GlobalIdentifier should exist.');

        $xml = '<Category>
                    <Name>Rectangles</Name>
                    <CategoryId>589</CategoryId>
                    <AttributeSetId>1</AttributeSetId>
                </Category>';

        CategoryFactory::make(new SimpleXMLElement($xml));
    }

    public function testItReturnsAJsonRepresentation(): void
    {
        $id = 589;
        $name = 'Rectangles';
        $setId = 1;
        $global = 'RectanglesGlobal';

        $simpleXml = simplexml_load_string(sprintf('<Category>
                    <Name>%s</Name>
                    <CategoryId>%d</CategoryId>
                    <GlobalIdentifier>%s</GlobalIdentifier>
                    <AttributeSetId>%d</AttributeSetId>
                </Category>', $name, $id, $global, $setId));

        $category = CategoryFactory::make($simpleXml);

        $expectedJson = sprintf('{"categoryId": %d, "name": "%s", "globalIdentifier": "%s", "attributeSetId": %d, "children": [], "attributes": []}', $id, $name, $global, $setId);
        $this->assertJsonStringEqualsJsonString($expectedJson, Json::encode($category));
    }
}

<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Category;

use Linio\Component\Util\Json;
use Linio\SellerCenter\Exception\InvalidXmlStructureException;
use Linio\SellerCenter\Factory\Xml\Category\CategoryAttributeFactory;
use Linio\SellerCenter\LinioTestCase;
use Linio\SellerCenter\Model\Category\CategoryAttribute;
use Linio\SellerCenter\Model\Category\CategoryAttributeOption;
use Linio\SellerCenter\Model\Category\CategoryAttributeOptions;

class CategoryAttributeTest extends LinioTestCase
{
    /**
     * @dataProvider categoryTypesProvider
     */
    public function testLoadsACategoryAttributeFromAnXml(
        $label,
        $name,
        $feedName,
        $globalIdentifier,
        $groupName,
        $mandatory,
        $globalAttribute,
        $description,
        $productType,
        $inputType,
        $attributeType,
        $exampleValue,
        $maxLength
    ): void {
        $xml = sprintf(
            '<Attribute>
                  <Label>%s</Label>
                  <Name>%s</Name>
                  <FeedName>%s</FeedName>
                  <GlobalIdentifier>%s</GlobalIdentifier>
                  <GroupName>%s</GroupName>
                  <isMandatory>%s</isMandatory>
                  <IsGlobalAttribute>%s</IsGlobalAttribute>
                  <Description>%s</Description>
                  <ProductType>%s</ProductType>
                  <InputType>%s</InputType>
                  <AttributeType>%s</AttributeType>
                  <ExampleValue>%s</ExampleValue>
                  <MaxLength>%s</MaxLength>
                  <Options/>
                </Attribute>',
            $label,
            $name,
            $feedName,
            $globalIdentifier,
            $groupName,
            $mandatory,
            $globalAttribute,
            $description,
            $productType,
            $inputType,
            $attributeType,
            $exampleValue,
            $maxLength
        );

        $sxml = simplexml_load_string($xml);

        $result = CategoryAttributeFactory::make($sxml);

        $this->assertInstanceOf(CategoryAttribute::class, $result);
        $this->assertEquals($label, $result->getLabel());
        $this->assertEquals($name, $result->getName());
        $this->assertEquals($feedName, $result->getFeedName());
        $this->assertEquals($globalIdentifier, $result->getGlobalIdentifier());
        $this->assertEquals($groupName, $result->getGroupName());
        $this->assertEquals((bool) $mandatory, $result->isMandatory());
        $this->assertEquals((bool) $globalAttribute, $result->isGlobalAttribute());
        $this->assertEquals($description, $result->getDescription());
        $this->assertEquals($productType, $result->getProductType());
        $this->assertEquals($inputType, $result->getInputType());
        $this->assertEquals($attributeType, $result->getAttributeType());
        $this->assertEquals($exampleValue, $result->getExampleValue());
        $this->assertEquals($maxLength, $result->getMaxLength());
    }

    public function categoryTypesProvider()
    {
        return [
            [
                'Memory Size (GB)',
                'StorageCapacity',
                'storage_capacity',
                'storage_capacity_mock',
                'Memory Size',
                0,
                0,
                'Size capacity of the phone',
                'config',
                'string',
                'value',
                '16GB',
                15,
            ],
            [
                'Memory Size (GB)',
                'StorageCapacity',
                'storage_capacity',
                'storage_capacity_mock',
                'Memory Size',
                1,
                0,
                'Size capacity of the phone',
                'config',
                'string',
                'value',
                '16GB',
                15,
            ],
            [
                'Memory Size (GB)',
                'StorageCapacity',
                'storage_capacity',
                'storage_capacity_mock',
                'Memory Size',
                '0',
                '0',
                'Size capacity of the phone',
                'config',
                'string',
                'value',
                '16GB',
                '15',
            ],
        ];
    }

    /**
     * @dataProvider categoryTypesProvider
     */
    public function testLoadsACategoryAttributeFromAnXmlWithoutOptionalValues(
        $label,
        $name,
        $feedName,
        $globalIdentifier,
        $groupName,
        $mandatory,
        $globalAttribute,
        $description,
        $productType,
        $inputType,
        $attributeType,
        $exampleValue,
        $maxLength
    ): void {
        $xml = sprintf(
            '<Attribute>
                  <Label>%s</Label>
                  <Name>%s</Name>
                  <FeedName>%s</FeedName>
                  <GlobalIdentifier>%s</GlobalIdentifier>
                  <GroupName/>
                  <isMandatory/>
                  <IsGlobalAttribute/>
                  <Description/>
                  <ProductType/>
                  <InputType/>
                  <AttributeType>%s</AttributeType>
                  <ExampleValue/>
                  <MaxLength/>
                  <Options/>
                </Attribute>',
            $label,
            $name,
            $feedName,
            $globalIdentifier,
            $attributeType
        );

        $sxml = simplexml_load_string($xml);

        $result = CategoryAttributeFactory::make($sxml);

        $this->assertInstanceOf(CategoryAttribute::class, $result);
        $this->assertEquals($label, $result->getLabel());
        $this->assertEquals($name, $result->getName());
        $this->assertEquals($globalIdentifier, $result->getGlobalIdentifier());
        $this->assertNull($result->getDescription());
        $this->assertFalse($result->isMandatory());
        $this->assertFalse($result->isGlobalAttribute());
        $this->assertEquals($attributeType, $result->getAttributeType());
        $this->assertNull($result->getExampleValue());
    }

    public function testItThrowsAnExceptionWithoutLabel(): void
    {
        $this->expectException(InvalidXmlStructureException::class);
        $this->expectExceptionMessage('The xml structure is not valid for a CategoryAttribute. The property Label should exist.');

        $xml =
            '<Attribute>
               <Name>tax_class</Name>
               <FeedName>TaxClass</FeedName>
               <GlobalIdentifier>101010200240</GlobalIdentifier>
               <GroupName>Garantía y Envío</GroupName>
               <isMandatory>1</isMandatory>
               <IsGlobalAttribute>1</IsGlobalAttribute>
               <Description>Taxes of the product if applicable</Description>
               <ProductType>simple</ProductType>
               <InputType>dropdown</InputType>
               <AttributeType>system</AttributeType>
               <ExampleValue>***21%</ExampleValue>
               <MaxLength/>
               <Options/>
          </Attribute>';

        $sxml = simplexml_load_string($xml);

        CategoryAttributeFactory::make($sxml);
    }

    public function testItThrowsAnExceptionWithoutName(): void
    {
        $this->expectException(InvalidXmlStructureException::class);
        $this->expectExceptionMessage('The xml structure is not valid for a CategoryAttribute. The property Name should exist.');

        $xml =
            '<Attribute>
               <Label>Taxes</Label>
               <FeedName>TaxClass</FeedName>
               <GlobalIdentifier>101010200240</GlobalIdentifier>
               <GroupName>Garantía y Envío</GroupName>
               <isMandatory>1</isMandatory>
               <IsGlobalAttribute>1</IsGlobalAttribute>
               <Description>Taxes of the product if applicable</Description>
               <ProductType>simple</ProductType>
               <InputType>dropdown</InputType>
               <AttributeType>system</AttributeType>
               <ExampleValue>***21%</ExampleValue>
               <MaxLength/>
               <Options/>
            </Attribute>';

        $sxml = simplexml_load_string($xml);

        CategoryAttributeFactory::make($sxml);
    }

    public function testItThrowsAnExceptionWithoutFeedName(): void
    {
        $this->expectException(InvalidXmlStructureException::class);
        $this->expectExceptionMessage('The xml structure is not valid for a CategoryAttribute. The property FeedName should exist.');

        $xml =
            '<Attribute>
               <Label>Taxes</Label>
               <Name>tax_class</Name>
               <GlobalIdentifier>101010200240</GlobalIdentifier>
               <GroupName>Garantía y Envío</GroupName>
               <isMandatory>1</isMandatory>
               <IsGlobalAttribute>1</IsGlobalAttribute>
               <Description>Taxes of the product if applicable</Description>
               <ProductType>simple</ProductType>
               <InputType>dropdown</InputType>
               <AttributeType>system</AttributeType>
               <ExampleValue>***21%</ExampleValue>
               <MaxLength/>
               <Options/>
            </Attribute>';

        $sxml = simplexml_load_string($xml);

        CategoryAttributeFactory::make($sxml);
    }

    public function testItThrowsAnExceptionWithoutGlobalIdentifier(): void
    {
        $this->expectException(InvalidXmlStructureException::class);
        $this->expectExceptionMessage('The xml structure is not valid for a CategoryAttribute. The property GlobalIdentifier should exist.');

        $xml =
            '<Attribute>
               <Label>Taxes</Label>
               <Name>tax_class</Name>
               <FeedName>TaxClass</FeedName>
               <GroupName>Garantía y Envío</GroupName>
               <isMandatory>1</isMandatory>
               <IsGlobalAttribute>1</IsGlobalAttribute>
               <Description>Taxes of the product if applicable</Description>
               <ProductType>simple</ProductType>
               <InputType>dropdown</InputType>
               <AttributeType>system</AttributeType>
               <ExampleValue>***21%</ExampleValue>
               <MaxLength/>
               <Options/>
            </Attribute>';

        $sxml = simplexml_load_string($xml);

        CategoryAttributeFactory::make($sxml);
    }

    public function testItThrowsAnExceptionWithoutGroupName(): void
    {
        $this->expectException(InvalidXmlStructureException::class);
        $this->expectExceptionMessage('The xml structure is not valid for a CategoryAttribute. The property GroupName should exist.');

        $xml =
            '<Attribute>
               <Label>Taxes</Label>
               <Name>tax_class</Name>
               <FeedName>TaxClass</FeedName>
                <GlobalIdentifier>101010200240</GlobalIdentifier>
               <isMandatory>1</isMandatory>
               <IsGlobalAttribute>1</IsGlobalAttribute>
               <Description>Taxes of the product if applicable</Description>
               <ProductType>simple</ProductType>
               <InputType>dropdown</InputType>
               <AttributeType>system</AttributeType>
               <ExampleValue>***21%</ExampleValue>
               <MaxLength/>
               <Options/>
            </Attribute>';

        $sxml = simplexml_load_string($xml);

        CategoryAttributeFactory::make($sxml);
    }

    public function testItThrowsAnExceptionWithoutMandatory(): void
    {
        $this->expectException(InvalidXmlStructureException::class);
        $this->expectExceptionMessage('The xml structure is not valid for a CategoryAttribute. The property isMandatory should exist.');

        $xml =
            '<Attribute>
               <Label>Taxes</Label>
               <Name>tax_class</Name>
               <FeedName>TaxClass</FeedName>
               <GlobalIdentifier>101010200240</GlobalIdentifier>
               <GroupName>Garantía y Envío</GroupName>
               <IsGlobalAttribute>1</IsGlobalAttribute>
               <Description>Taxes of the product if applicable</Description>
               <ProductType>simple</ProductType>
               <InputType>dropdown</InputType>
               <AttributeType>system</AttributeType>
               <ExampleValue>***21%</ExampleValue>
               <MaxLength/>
               <Options/>
            </Attribute>';

        $sxml = simplexml_load_string($xml);

        CategoryAttributeFactory::make($sxml);
    }

    public function testItThrowsAnExceptionWithoutGlobalAttribute(): void
    {
        $this->expectException(InvalidXmlStructureException::class);
        $this->expectExceptionMessage('The xml structure is not valid for a CategoryAttribute. The property IsGlobalAttribute should exist.');

        $xml =
            '<Attribute>
               <Label>Taxes</Label>
               <Name>tax_class</Name>
               <FeedName>TaxClass</FeedName>
               <GlobalIdentifier>101010200240</GlobalIdentifier>
               <GroupName>Garantía y Envío</GroupName>
               <isMandatory>1</isMandatory>
               <Description>Taxes of the product if applicable</Description>
               <ProductType>simple</ProductType>
               <InputType>dropdown</InputType>
               <AttributeType>system</AttributeType>
               <ExampleValue>***21%</ExampleValue>
               <MaxLength/>
               <Options/>
            </Attribute>';

        $sxml = simplexml_load_string($xml);

        CategoryAttributeFactory::make($sxml);
    }

    public function testItThrowsAnExceptionWithoutDescription(): void
    {
        $this->expectException(InvalidXmlStructureException::class);
        $this->expectExceptionMessage('The xml structure is not valid for a CategoryAttribute. The property Description should exist.');

        $xml =
            '<Attribute>
               <Label>Taxes</Label>
               <Name>tax_class</Name>
               <FeedName>TaxClass</FeedName>
               <GlobalIdentifier>101010200240</GlobalIdentifier>
               <GroupName>Garantía y Envío</GroupName>
               <isMandatory>1</isMandatory>
               <IsGlobalAttribute>1</IsGlobalAttribute>
               <ProductType>simple</ProductType>
               <InputType>dropdown</InputType>
               <AttributeType>system</AttributeType>
               <ExampleValue>***21%</ExampleValue>
               <MaxLength/>
               <Options/>
            </Attribute>';

        $sxml = simplexml_load_string($xml);

        CategoryAttributeFactory::make($sxml);
    }

    public function testItThrowsAnExceptionWithoutProductType(): void
    {
        $this->expectException(InvalidXmlStructureException::class);
        $this->expectExceptionMessage('The xml structure is not valid for a CategoryAttribute. The property ProductType should exist.');

        $xml =
            '<Attribute>
               <Label>Taxes</Label>
               <Name>tax_class</Name>
               <FeedName>TaxClass</FeedName>
               <GlobalIdentifier>101010200240</GlobalIdentifier>
               <GroupName>Garantía y Envío</GroupName>
               <isMandatory>1</isMandatory>
               <IsGlobalAttribute>1</IsGlobalAttribute>
               <Description>Taxes of the product if applicable</Description>
               <InputType>dropdown</InputType>
               <AttributeType>system</AttributeType>
               <ExampleValue>***21%</ExampleValue>
               <MaxLength/>
               <Options/>
            </Attribute>';

        $sxml = simplexml_load_string($xml);

        CategoryAttributeFactory::make($sxml);
    }

    public function testItThrowsAnExceptionWithoutInputType(): void
    {
        $this->expectException(InvalidXmlStructureException::class);
        $this->expectExceptionMessage('The xml structure is not valid for a CategoryAttribute. The property InputType should exist.');

        $xml =
            '<Attribute>
               <Label>Taxes</Label>
               <Name>tax_class</Name>
               <FeedName>TaxClass</FeedName>
               <GlobalIdentifier>101010200240</GlobalIdentifier>
               <GroupName>Garantía y Envío</GroupName>
               <isMandatory>1</isMandatory>
               <IsGlobalAttribute>1</IsGlobalAttribute>
               <Description>Taxes of the product if applicable</Description>
               <ProductType>simple</ProductType>
               <AttributeType>system</AttributeType>
               <ExampleValue>***21%</ExampleValue>
               <MaxLength/>
               <Options/>
            </Attribute>';

        $sxml = simplexml_load_string($xml);

        CategoryAttributeFactory::make($sxml);
    }

    public function testItThrowsAnExceptionWithoutAttributeType(): void
    {
        $this->expectException(InvalidXmlStructureException::class);
        $this->expectExceptionMessage('The xml structure is not valid for a CategoryAttribute. The property AttributeType should exist.');

        $xml =
            '<Attribute>
               <Label>Taxes</Label>
               <Name>tax_class</Name>
               <FeedName>TaxClass</FeedName>
               <GlobalIdentifier>101010200240</GlobalIdentifier>
               <GroupName>Garantía y Envío</GroupName>
               <isMandatory>1</isMandatory>
               <IsGlobalAttribute>1</IsGlobalAttribute>
               <Description>Taxes of the product if applicable</Description>
               <ProductType>simple</ProductType>
               <InputType>dropdown</InputType>
               <ExampleValue>***21%</ExampleValue>
               <MaxLength/>
               <Options/>
            </Attribute>';

        $sxml = simplexml_load_string($xml);

        CategoryAttributeFactory::make($sxml);
    }

    public function testItThrowsAnExceptionWithoutExampleValue(): void
    {
        $this->expectException(InvalidXmlStructureException::class);
        $this->expectExceptionMessage('The xml structure is not valid for a CategoryAttribute. The property ExampleValue should exist.');

        $xml =
            '<Attribute>
               <Label>Taxes</Label>
               <Name>tax_class</Name>
               <FeedName>TaxClass</FeedName>
               <GlobalIdentifier>101010200240</GlobalIdentifier>
               <GroupName>Garantía y Envío</GroupName>
               <isMandatory>1</isMandatory>
               <IsGlobalAttribute>1</IsGlobalAttribute>
               <Description>Taxes of the product if applicable</Description>
               <ProductType>simple</ProductType>
               <InputType>dropdown</InputType>
               <AttributeType>system</AttributeType>
               <MaxLength/>
               <Options/>
            </Attribute>';

        $sxml = simplexml_load_string($xml);

        CategoryAttributeFactory::make($sxml);
    }

    public function testItThrowsAnExceptionWithoutMaxLength(): void
    {
        $this->expectException(InvalidXmlStructureException::class);
        $this->expectExceptionMessage('The xml structure is not valid for a CategoryAttribute. The property MaxLength should exist.');

        $xml =
            '<Attribute>
               <Label>Taxes</Label>
               <Name>tax_class</Name>
               <FeedName>TaxClass</FeedName>
               <GlobalIdentifier>101010200240</GlobalIdentifier>
               <GroupName>Garantía y Envío</GroupName>
               <isMandatory>1</isMandatory>
               <IsGlobalAttribute>1</IsGlobalAttribute>
               <Description>Taxes of the product if applicable</Description>
               <ProductType>simple</ProductType>
               <InputType>dropdown</InputType>
               <AttributeType>system</AttributeType>
               <ExampleValue>***21%</ExampleValue>
               <Options/>
            </Attribute>';

        $sxml = simplexml_load_string($xml);

        CategoryAttributeFactory::make($sxml);
    }

    public function testItThrowsAnExceptionWithoutOptions(): void
    {
        $this->expectException(InvalidXmlStructureException::class);
        $this->expectExceptionMessage('The xml structure is not valid for a CategoryAttribute. The property Options should exist.');

        $xml =
            '<Attribute>
               <Label>Taxes</Label>
               <Name>tax_class</Name>
               <FeedName>TaxClass</FeedName>
               <GlobalIdentifier>101010200240</GlobalIdentifier>
               <GroupName>Garantía y Envío</GroupName>
               <isMandatory>1</isMandatory>
               <IsGlobalAttribute>1</IsGlobalAttribute>
               <Description>Taxes of the product if applicable</Description>
               <ProductType>simple</ProductType>
               <InputType>dropdown</InputType>
               <AttributeType>system</AttributeType>
               <ExampleValue>***21%</ExampleValue>
               <MaxLength/>
            </Attribute>';

        $sxml = simplexml_load_string($xml);

        CategoryAttributeFactory::make($sxml);
    }

    public function testItReturnsTheCategoryAttributeObjectWithTheDefaultParameters(): void
    {
        $options = $this->prophesize(CategoryAttributeOptions::class);

        $categoryAttribute = new CategoryAttribute(
            'name',
            'feedname',
            'label',
            'global-identifier',
            'attributeType',
            $options->reveal()
        );

        $this->assertFalse($categoryAttribute->isMandatory());
        $this->assertFalse($categoryAttribute->isGlobalAttribute());
        $this->assertNull($categoryAttribute->getDescription());
        $this->assertNull($categoryAttribute->getProductType());
        $this->assertNull($categoryAttribute->getInputType());
        $this->assertNull($categoryAttribute->getGroupName());
        $this->assertNull($categoryAttribute->getMaxLength());
        $this->assertNull($categoryAttribute->getExampleValue());
    }

    public function testItReturnsAJsonRepresentation(): void
    {
        $options = new CategoryAttributeOptions();
        $option = new CategoryAttributeOption('globalIdentifier', 'name', true);
        $options->add($option);

        $name = 'name';
        $feedName = 'feedname';
        $label = 'label';
        $globalIdentifier = 'global-identifier';
        $attributeType = 'attributeType';
        $categoryAttribute = new CategoryAttribute(
            $name,
            $feedName,
            $label,
            $globalIdentifier,
            $attributeType,
            $options
        );

        $expectedJson = sprintf('{"name":"%s","feedName":"%s","label":"%s","globalIdentifier":"%s","mandatory":false,"globalAttribute":false,"description":null,"productType":null,"inputType":null,"attributeType":"%s","options": [ {"globalIdentifier": "globalIdentifier", "name": "name", "default": true } ],"groupName":null,"maxLength":null,"exampleValue":null}', $name, $feedName, $label, $globalIdentifier, $attributeType);
        $this->assertJsonStringEqualsJsonString($expectedJson, Json::encode($categoryAttribute));
    }
}

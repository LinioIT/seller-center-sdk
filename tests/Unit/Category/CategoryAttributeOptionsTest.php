<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Category;

use Linio\SellerCenter\Factory\Xml\Category\CategoryAttributeFactory;
use Linio\SellerCenter\LinioTestCase;
use Linio\SellerCenter\Model\Category\CategoryAttributeOption;
use Linio\SellerCenter\Model\Category\CategoryAttributeOptions;

class CategoryAttributeOptionsTest extends LinioTestCase
{
    /**
     * @dataProvider elementsWithAndWithoutOptions
     */
    public function testItReturnsACategoryAttributeOptionsObject(string $xml): void
    {
        $sxml = simplexml_load_string($xml);

        $categoryAttribute = CategoryAttributeFactory::make($sxml);

        $attributeOptions = $categoryAttribute->getOptions();

        $this->assertInstanceOf(CategoryAttributeOptions::class, $attributeOptions);
    }

    public function testItLoadsAnAttributeOption(): void
    {
        $xml = '<Attribute>
               <Label>Impuestos</Label>
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
               <ExampleValue>***IVA 19%</ExampleValue>
               <MaxLength/>
               <Options>
                    <Option>
                         <GlobalIdentifier/>
                         <Name>IVA 19%</Name>
                         <isDefault>1</isDefault>
                    </Option>
                    <Option>
                         <GlobalIdentifier/>
                         <Name>0%</Name>
                         <isDefault>0</isDefault>
                    </Option>
               </Options>
          </Attribute>';

        $sxml = simplexml_load_string($xml);

        $categoryAttribute = CategoryAttributeFactory::make($sxml);

        $options = $categoryAttribute->getOptions()->all();

        $this->assertContainsOnlyInstancesOf(CategoryAttributeOption::class, $options);
        $this->assertCount(2, $options);

        $this->assertNull($options[0]->getGlobalIdentifier());
        $this->assertEquals('IVA 19%', $options[0]->getName());
        $this->assertTrue($options[0]->isDefault());

        $this->assertNull($options[1]->getGlobalIdentifier());
        $this->assertEquals('0%', $options[1]->getName());
        $this->assertFalse($options[1]->isDefault());
    }

    public function elementsWithAndWithoutOptions(): array
    {
        return [
            [
                '<Attribute>
                   <Label>Impuestos</Label>
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
                   <ExampleValue>***IVA 19%</ExampleValue>
                   <MaxLength/>
                   <Options>
                        <Option>
                             <GlobalIdentifier/>
                             <Name>IVA 19%</Name>
                             <isDefault>1</isDefault>
                        </Option>
                        <Option>
                             <GlobalIdentifier/>
                             <Name>0%</Name>
                             <isDefault>0</isDefault>
                        </Option>
                   </Options>
                </Attribute>',
            ],
            [
                '<Attribute>
                       <Label>Impuestos</Label>
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
                       <ExampleValue>***IVA 19%</ExampleValue>
                       <MaxLength/>
                       <Options/>
                  </Attribute>',
            ],
        ];
    }
}

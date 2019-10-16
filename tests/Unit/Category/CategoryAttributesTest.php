<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Category;

use Linio\SellerCenter\Exception\InvalidXmlStructureException;
use Linio\SellerCenter\Factory\Xml\Category\CategoryAttributesFactory;
use Linio\SellerCenter\LinioTestCase;
use Linio\SellerCenter\Model\Category\CategoryAttribute;
use Linio\SellerCenter\Model\Category\CategoryAttributes;

class CategoryAttributesTest extends LinioTestCase
{
    public function testItReturnsACollectionOfCategoryAttributesFromAnXml(): void
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>
                <SuccessResponse>
                  <Head>
                    <RequestId/>
                    <RequestAction>GetCategoryAttributes</RequestAction>
                    <ResponseType>Attributes</ResponseType>
                    <Timestamp>2015-07-01T11:11:11+0000</Timestamp>
                  </Head>
                  <Body>
                    <Attribute>
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
                       <Options>
                            <Option>
                                 <GlobalIdentifier/>
                                 <Name>10.50%</Name>
                                 <isDefault>1</isDefault>
                            </Option>
                            <Option>
                                 <GlobalIdentifier/>
                                 <Name>21%</Name>
                                 <isDefault>0</isDefault>
                            </Option>
                            <Option>
                                 <GlobalIdentifier/>
                                 <Name>0%</Name>
                                 <isDefault>0</isDefault>
                            </Option>
                       </Options>
                    </Attribute>
                    <Attribute>
                       <Label>Name</Label>
                       <Name>name</Name>
                       <FeedName>Name</FeedName>
                       <GlobalIdentifier>101010200232</GlobalIdentifier>
                       <GroupName>Main</GroupName>
                       <isMandatory>1</isMandatory>
                       <IsGlobalAttribute>1</IsGlobalAttribute>
                       <Description>Structure: What is the product + Brand + Model / Reference - Color</Description>
                       <ProductType>config</ProductType>
                       <InputType>textfield</InputType>
                       <AttributeType>system</AttributeType>
                       <ExampleValue>***Celular Samsung Galaxy S7 Edge 32GB - Gris</ExampleValue>
                       <MaxLength>255</MaxLength>
                       <Options/>
                    </Attribute>
                    <Attribute>
                       <Label>Brand</Label>
                       <Name>brand</Name>
                       <FeedName>Brand</FeedName>
                       <GlobalIdentifier>101010200233</GlobalIdentifier>
                       <GroupName>Main</GroupName>
                       <isMandatory>1</isMandatory>
                       <IsGlobalAttribute>1</IsGlobalAttribute>
                       <Description>Brand of the product, if it doesn\'t exist in the list, request it through socios.ar@linio.com</Description>
                       <ProductType>config</ProductType>
                       <InputType>dropdown</InputType>
                       <AttributeType>system</AttributeType>
                       <ExampleValue>***Samsung (Selecciona una opción)</ExampleValue>
                       <MaxLength/>
                       <Options/>
                    </Attribute>
                    <Attribute>
                       <Label>Modelo</Label>
                       <Name>model</Name>
                       <FeedName>Model</FeedName>
                       <GlobalIdentifier>101010200299</GlobalIdentifier>
                       <GroupName>Main</GroupName>
                       <isMandatory>0</isMandatory>
                       <IsGlobalAttribute>1</IsGlobalAttribute>
                       <Description>Model of the product</Description>
                       <ProductType>config</ProductType>
                       <InputType>textfield</InputType>
                       <AttributeType>value</AttributeType>
                       <ExampleValue>S7, UN46FH5303 (escribe texto y/o número)</ExampleValue>
                       <MaxLength>255</MaxLength>
                       <Options/>
                    </Attribute>
                  </Body>  
                </SuccessResponse>';

        $sxml = simplexml_load_string($xml);

        $result = CategoryAttributesFactory::make($sxml->Body);

        $this->assertInstanceOf(CategoryAttributes::class, $result);
        $this->assertContainsOnlyInstancesOf(CategoryAttribute::class, $result->all());
        $this->assertCount(4, $result->all());
    }

    public function testItThrowsAnExceptionWithoutAtLeastOneAttributeField(): void
    {
        $this->expectException(InvalidXmlStructureException::class);
        $this->expectExceptionMessage('The xml structure is not valid for a CategoryAttributes. The property Attribute should exist.');

        $xml = '<?xml version="1.0" encoding="UTF-8"?>
                <SuccessResponse>
                  <Head>
                    <RequestId/>
                    <RequestAction>GetCategoryAttributes</RequestAction>
                    <ResponseType>Attributes</ResponseType>
                    <Timestamp>2015-07-01T11:11:11+0000</Timestamp>
                  </Head>
                  <Body>
                  </Body>  
                </SuccessResponse>';

        $sxml = simplexml_load_string($xml);

        CategoryAttributesFactory::make($sxml->Body);
    }
}

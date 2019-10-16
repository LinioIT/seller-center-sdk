<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Category;

use Linio\Component\Util\Json;
use Linio\SellerCenter\Exception\InvalidXmlStructureException;
use Linio\SellerCenter\Factory\Xml\Category\CategoryAttributeOptionFactory;
use Linio\SellerCenter\LinioTestCase;

class CategoryAttributeOptionTest extends LinioTestCase
{
    public function testItThrowsAnExceptionWithoutTheGlobalIdentifierField(): void
    {
        $this->expectException(InvalidXmlStructureException::class);
        $this->expectExceptionMessage('The xml structure is not valid for a Option. The property GlobalIdentifier should exist.');

        $xml = '<Option>
                  <Name>NVIDIA</Name>
                  <isDefault>0</isDefault>
                </Option>';

        $sxml = simplexml_load_string($xml);

        CategoryAttributeOptionFactory::make($sxml);
    }

    public function testItThrowsAnExceptionWithoutTheNameField(): void
    {
        $this->expectException(InvalidXmlStructureException::class);
        $this->expectExceptionMessage('The xml structure is not valid for a Option. The property Name should exist.');

        $xml = '<Option>
                  <GlobalIdentifier/>
                  <isDefault>0</isDefault>
                </Option>';

        $sxml = simplexml_load_string($xml);

        CategoryAttributeOptionFactory::make($sxml);
    }

    public function testItThrowsAnExceptionWithoutTheIsDefaultField(): void
    {
        $this->expectException(InvalidXmlStructureException::class);
        $this->expectExceptionMessage('The xml structure is not valid for a Option. The property isDefault should exist.');

        $xml = '<Option>
                  <GlobalIdentifier/>
                  <Name>NVIDIA</Name>
                </Option>';

        $sxml = simplexml_load_string($xml);

        CategoryAttributeOptionFactory::make($sxml);
    }

    public function testItReturnsAJsonRepresentation(): void
    {
        $name = 'NVIDIA';

        $xml = '<Option>
                  <GlobalIdentifier/>
                  <Name>' . $name . '</Name>
                  <isDefault/>
                </Option>';

        $simpleXml = simplexml_load_string($xml);

        $categoryAttributeOption = CategoryAttributeOptionFactory::make($simpleXml);

        $expectedJson = sprintf('{"globalIdentifier": null, "name": "%s", "default": false}', $name);
        $this->assertJsonStringEqualsJsonString($expectedJson, Json::encode($categoryAttributeOption));
    }
}

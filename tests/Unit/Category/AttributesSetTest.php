<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Category;

use Linio\SellerCenter\Exception\InvalidXmlStructureException;
use Linio\SellerCenter\Factory\Xml\Category\AttributesSetFactory;
use Linio\SellerCenter\LinioTestCase;
use Linio\SellerCenter\Model\Category\AttributeSet;

class AttributesSetTest extends LinioTestCase
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
    }

    public function testItThrowsAnExceptionWithAnEmptyAttributeSets(): void
    {
        $this->expectException(InvalidXmlStructureException::class);
        $this->expectExceptionMessage('The xml structure is not valid for a AttributeSets. The property AttributeSet should exist.');

        $success = '<?xml version="1.0" encoding="UTF-8"?>
                    <SuccessResponse>
                      <Head>
                        <RequestId/>
                        <RequestAction>GetCategoriesByAttributeSet</RequestAction>
                        <ResponseType>AttributeSets</ResponseType>
                        <Timestamp>2015-07-16T05:19:15+0200</Timestamp>
                      </Head>
                      <Body>
                      </Body>
                    </SuccessResponse>';

        $xml = simplexml_load_string($success);
        $attributeSets = AttributesSetFactory::make($xml->Body);

        $result = $attributeSets->all();
        $this->assertNotEmpty($result);
        $this->assertContainsOnlyInstancesOf(AttributeSet::class, $result);
    }
}

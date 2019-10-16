<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Response;

use Linio\SellerCenter\LinioTestCase;
use SimpleXMLElement;

class SuccessResponseTest extends LinioTestCase
{
    public function testItLoadsASuccessResponseXml(): void
    {
        $xml = simplexml_load_string('<?xml version="1.0" encoding="UTF-8"?>
                    <SuccessResponse>
                      <Head>
                        <RequestId/>
                        <RequestAction>GetBrands</RequestAction>
                        <ResponseType>Brands</ResponseType>
                        <Timestamp>2015-07-01T11:11:11+0000</Timestamp>
                      </Head>
                      <Body>
                        <Brands>
                          <Brand>
                            <BrandId>1</BrandId>
                            <Name>Commodore</Name>
                            <GlobalIdentifier>commodore</GlobalIdentifier>
                          </Brand>
                          <Brand>
                            <BrandId>2</BrandId>
                            <Name>Atari</Name>
                            <GlobalIdentifier/>
                          </Brand>
                        </Brands>
                      </Body>
                    </SuccessResponse>');

        $success = SuccessResponse::fromXml($xml);

        $this->assertInstanceOf(SimpleXMLElement::class, $success->getBody());
        $this->assertEquals('GetBrands', $success->getHead()->RequestAction);
    }
}

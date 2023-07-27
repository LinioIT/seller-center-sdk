<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Xml\Seller;

use Linio\Component\Util\Json;
use Linio\SellerCenter\LinioTestCase;
use Linio\SellerCenter\Model\Seller\Seller;

class SellerFactoryTest extends LinioTestCase
{
    public function testItProcessAnXml(): void
    {
        $stringXml = $this->getSchema('Seller/GetSellerByUserSuccessResponse.xml');
        $xml = simplexml_load_string($stringXml);

        $seller = SellerFactory::make($xml->Body);

        $this->assertJsonStringEqualsJsonString(
            $this->getSchema('Seller/Seller.json'),
            Json::encode($seller)
        );

        $this->assertInstanceOf(Seller::class, $seller);
    }
}

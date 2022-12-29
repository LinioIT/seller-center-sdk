<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Unit\Factory;

use Linio\SellerCenter\Factory\Xml\Product\ProductsFactory;
use Linio\SellerCenter\LinioTestCase;
use Prophecy\Argument;
use Psr\Log\LoggerInterface;
use SimpleXMLElement;

class ProductsFactoryTest extends LinioTestCase
{
    public function testItLogsTheErrorsInTheXmlStructure(): void
    {
        $xmlString = $this->getSchema('Product/ErrorProductsResponse.xml');
        $xml = new SimpleXMLElement($xmlString);

        $logger = $this->prophesize(LoggerInterface::class);
        $logger->warning(
            Argument::type('string'),
            Argument::type('array')
        )->shouldBeCalled();

        ProductsFactory::make($xml->Body, $logger->reveal());
    }
}

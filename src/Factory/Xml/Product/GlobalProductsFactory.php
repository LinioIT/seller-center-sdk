<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Xml\Product;

use Exception;
use Linio\SellerCenter\Formatter\LogMessageFormatter;
use Linio\SellerCenter\Model\Product\Products;
use Psr\Log\LoggerInterface;
use SimpleXMLElement;

class GlobalProductsFactory
{
    public static function make(SimpleXMLElement $xml, ?LoggerInterface $logger = null): Products
    {
        $products = new Products();

        foreach ($xml->Products->Product as $item) {
            try {
                $product = GlobalProductFactory::make($item);
            } catch (Exception $e) {
                if (!empty($logger)) {
                    $logger->warning(
                        LogMessageFormatter::fromFactory(GlobalProductsFactory::class, LogMessageFormatter::TYPE_FACTORY),
                        [
                            'Exception' => (string) $e->getMessage(),
                        ]
                    );
                }
                continue;
            }

            $products->add($product);
        }

        return $products;
    }
}

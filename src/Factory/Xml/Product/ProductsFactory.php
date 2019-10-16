<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Xml\Product;

use Exception;
use Linio\SellerCenter\Model\Product\Products;
use SimpleXMLElement;

class ProductsFactory
{
    public static function make(SimpleXMLElement $xml): Products
    {
        $products = new Products();

        foreach ($xml->Products->Product as $item) {
            try {
                $product = ProductFactory::make($item);
            } catch (Exception $e) {
                continue;
            }

            $products->add($product);
        }

        return $products;
    }
}

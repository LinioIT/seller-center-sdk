<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Transformer\Product;

use Linio\SellerCenter\Model\Product\Products;
use SimpleXMLElement;

class ProductsTransformer
{
    public static function asXml(Products $products): SimpleXMLElement
    {
        $xml = new SimpleXMLElement('<Request/>');

        foreach ($products->all() as $product) {
            ProductTransformer::asXml($xml, $product);
        }

        return $xml;
    }

    public static function asXmlString(Products $products): string
    {
        $xml = new SimpleXMLElement('<Request/>');

        foreach ($products->all() as $product) {
            ProductTransformer::asXml($xml, $product);
        }

        return (string) $xml->asXML();
    }

    public static function skusAsXml(Products $products): SimpleXMLElement
    {
        $xml = new SimpleXMLElement('<Request/>');

        foreach ($products->all() as $product) {
            ProductTransformer::skuAsXml($xml, $product);
        }

        return $xml;
    }

    public static function skusAsXmlString(Products $products): string
    {
        $xml = new SimpleXMLElement('<Request/>');

        foreach ($products->all() as $product) {
            ProductTransformer::skuAsXml($xml, $product);
        }

        return (string) $xml->asXML();
    }

    public static function imagesAsXml(Products $products): SimpleXMLElement
    {
        $xml = new SimpleXMLElement('<Resquest/>');

        foreach ($products->all() as $product) {
            ProductTransformer::imagesAsXml($xml, $product);
        }

        return $xml;
    }

    public static function imagesAsXmlString(Products $products): string
    {
        $xml = new SimpleXMLElement('<Resquest/>');

        foreach ($products->all() as $product) {
            ProductTransformer::imagesAsXml($xml, $product);
        }

        return (string) $xml->asXML();
    }
}

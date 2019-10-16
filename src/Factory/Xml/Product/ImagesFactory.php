<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Xml\Product;

use Linio\SellerCenter\Model\Product\Image;
use Linio\SellerCenter\Model\Product\Images;
use SimpleXMLElement;

class ImagesFactory
{
    public static function make(SimpleXMLElement $element): Images
    {
        $images = new Images();

        foreach ($element->Image as $url) {
            if (empty($url)) {
                continue;
            }

            $image = new Image((string) $url);
            $images->add($image);
        }

        return $images;
    }
}

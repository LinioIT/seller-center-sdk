<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Xml\Seller;

use Linio\SellerCenter\Model\Seller\Seller;
use SimpleXMLElement;

class SellerFactory
{
    public static function make(SimpleXMLElement $xml): Seller
    {
        return new Seller(
            (string) $xml->Seller->ShortCode,
            (string) $xml->Seller->CompanyName,
            (string) $xml->Seller->SellerName,
            (string) $xml->Seller->EmailAddress,
            (string) $xml->Seller->ApiKey
        );
    }
}

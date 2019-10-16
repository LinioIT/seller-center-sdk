<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model\Product;

use JsonSerializable;
use Linio\SellerCenter\Exception\InvalidUrlException;
use stdClass;

class Image implements JsonSerializable
{
    /**
     * @var string
     */
    protected $url;

    public function __construct(string $url)
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new InvalidUrlException($url);
        }

        $this->url = $url;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function jsonSerialize(): stdClass
    {
        $serialized = new stdClass();
        $serialized->url = $this->url;

        return $serialized;
    }
}

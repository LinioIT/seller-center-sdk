<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model\Feed;

use JsonSerializable;
use Linio\SellerCenter\Exception\EmptyArgumentException;
use stdClass;

class FeedWarning implements JsonSerializable
{
    /**
     * @var string
     */
    protected $sku;

    /**
     * @var string
     */
    protected $message;

    public function __construct(string $sellerSku, string $message)
    {
        if (empty($sellerSku)) {
            throw new EmptyArgumentException('SellerSku');
        }

        if (empty($message)) {
            throw new EmptyArgumentException('Message');
        }

        $this->sku = $sellerSku;
        $this->message = $message;
    }

    public function getSellerSku(): string
    {
        return $this->sku;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function jsonSerialize(): stdClass
    {
        $serialized = new stdClass();
        $serialized->sku = $this->sku;
        $serialized->message = $this->message;

        return $serialized;
    }
}

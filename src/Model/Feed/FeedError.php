<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model\Feed;

use JsonSerializable;
use Linio\SellerCenter\Exception\EmptyArgumentException;
use stdClass;

class FeedError implements JsonSerializable
{
    /**
     * @var int
     */
    protected $code;

    /**
     * @var string
     */
    protected $sku;

    /**
     * @var string
     */
    protected $message;

    public function __construct(int $code, string $sellerSku, string $message)
    {
        if (empty($message)) {
            throw new EmptyArgumentException('Message');
        }

        $this->code = $code;
        $this->sku = $sellerSku;
        $this->message = $message;
    }

    public function getCode(): int
    {
        return $this->code;
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
        $serialized->code = $this->code;
        $serialized->sku = $this->sku;
        $serialized->message = $this->message;

        return $serialized;
    }
}

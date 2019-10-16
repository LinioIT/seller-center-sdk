<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model\Order;

use JsonSerializable;
use stdClass;

class FailureReason implements JsonSerializable
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $name;

    public function __construct(string $type, string $name)
    {
        $this->type = $type;
        $this->name = $name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function jsonSerialize(): stdClass
    {
        $serialized = new stdClass();
        $serialized->type = $this->type;
        $serialized->name = $this->name;

        return $serialized;
    }
}

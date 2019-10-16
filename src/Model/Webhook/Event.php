<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model\Webhook;

use JsonSerializable;
use stdClass;

class Event implements JsonSerializable
{
    /**
     * @var string
     */
    protected $alias;

    /**
     * @var string|null
     */
    protected $name;

    public function __construct(string $alias, ?string $name)
    {
        $this->alias = $alias;
        $this->name = $name;
    }

    public function getAlias(): string
    {
        return $this->alias;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function jsonSerialize(): stdClass
    {
        $serialized = new stdClass();
        $serialized->alias = $this->alias;
        $serialized->name = $this->name;

        return $serialized;
    }
}

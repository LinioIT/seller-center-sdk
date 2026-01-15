<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model\Warehouse;

use JsonSerializable;
use stdClass;

class Contact implements JsonSerializable
{
    /**
     * @var string|null
     */
    protected $type;

    /**
     * @var string|null
     */
    protected $value;

    /**
     * @var string|null
     */
    protected $typeDescription;

    public function __construct(
        ?string $type,
        ?string $value,
        ?string $typeDescription
    ) {
        $this->type = $type;
        $this->value = $value;
        $this->typeDescription = $typeDescription;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function getTypeDescription(): ?string
    {
        return $this->typeDescription;
    }

    public function jsonSerialize(): stdClass
    {
        $serialized = new stdClass();
        $serialized->type = $this->type;
        $serialized->value = $this->value;
        $serialized->typeDescription = $this->typeDescription;

        return $serialized;
    }
}

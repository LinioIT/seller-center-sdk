<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model\Warehouse;

use JsonSerializable;
use stdClass;

class ShiftHours implements JsonSerializable
{
    /**
     * @var string
     */
    protected $openingHour;

    /**
     * @var string
     */
    protected $closingHour;

    public function __construct(string $openingHour, string $closingHour)
    {
        $this->openingHour = $openingHour;
        $this->closingHour = $closingHour;
    }

    public function getOpeningHour(): string
    {
        return $this->openingHour;
    }

    public function getClosingHour(): string
    {
        return $this->closingHour;
    }

    public function jsonSerialize(): stdClass
    {
        $serialized = new stdClass();
        $serialized->openingHour = $this->openingHour;
        $serialized->closingHour = $this->closingHour;

        return $serialized;
    }
}

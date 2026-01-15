<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model\Warehouse;

use JsonSerializable;
use stdClass;

class WorkingSchedule implements JsonSerializable
{
    /**
     *  @var string
     */
    protected $day;

    /**
     *  @var ShiftHours|null
     */
    protected $shiftHours;

    public function __construct(string $day, ?ShiftHours $shiftHours)
    {
        $this->day = $day;
        $this->shiftHours = $shiftHours;
    }

    public function getDay(): string
    {
        return $this->day;
    }

    public function getShiftHours(): ?ShiftHours
    {
        return $this->shiftHours;
    }

    public function jsonSerialize(): stdClass
    {
        $serialized = new stdClass();
        $serialized->day = $this->day;
        $serialized->shiftHours = $this->shiftHours;

        return $serialized;
    }
}

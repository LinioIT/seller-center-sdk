<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Xml\Warehouse;

use Linio\SellerCenter\Model\Warehouse\WorkingSchedule;
use SimpleXMLElement;

class WorkingScheduleFactory
{
    public static function make(SimpleXMLElement $xml): WorkingSchedule
    {
        $day = (string) $xml->day;
        $shiftHours = !empty($xml->shiftHours->shiftHours) ? ShiftHoursFactory::make($xml->shiftHours->shiftHours) : null;

        return new WorkingSchedule($day, $shiftHours);
    }
}

<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory\Xml\Warehouse;

use Linio\SellerCenter\Model\Warehouse\WorkingSchedules;
use SimpleXMLElement;

class WorkingSchedulesFactory
{
    public static function make(SimpleXMLElement $xml): WorkingSchedules
    {
        $workingSchedules = new WorkingSchedules();

        foreach ($xml->workingSchedule as $schedule) {
            $workingSchedules->add(WorkingScheduleFactory::make($schedule));
        }

        return $workingSchedules;
    }
}

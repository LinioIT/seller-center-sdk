<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model;

use Linio\Component\Util\Json;
use Linio\SellerCenter\Factory\Xml\Warehouse\ShiftHoursFactory;
use Linio\SellerCenter\Factory\Xml\Warehouse\WorkingSchedulesFactory;
use Linio\SellerCenter\LinioTestCase;
use Linio\SellerCenter\Model\Warehouse\ShiftHours;
use Linio\SellerCenter\Model\Warehouse\WorkingSchedule;
use Linio\SellerCenter\Model\Warehouse\WorkingSchedules;

class WorkingScheduleTest extends LinioTestCase
{
    public function testItReturnsValidWorkingSchedule(): void
    {
        $xml = '
                <workingSchedule>
                    <workingSchedule>
                        <day>monday</day>
                        <shiftHours>
                            <shiftHours>
                                <openingHour>08:00 AM</openingHour>
                                <closingHour>08:00 PM</closingHour>
                            </shiftHours>
                        </shiftHours>
                    </workingSchedule>
                </workingSchedule>
        ';

        $xml = simplexml_load_string($xml);
        $schedule = WorkingSchedulesFactory::make($xml);

        $this->assertInstanceOf(WorkingSchedules::class, $schedule);
        $this->assertIsArray($schedule->all());

        /**
         * @var WorkingSchedule $value
         */
        foreach ($schedule as $value) {
            $this->assertEquals('monday', $value->getDay());
            $this->assertInstanceOf(ShiftHours::class, $value->getShiftHours());
        }
    }

    public function testItReturnsValidShiftHours(): void
    {
        $xml = '
                <shiftHours>
                    <openingHour>08:00 AM</openingHour>
                    <closingHour>08:00 PM</closingHour>
                </shiftHours>
        ';

        $xml = simplexml_load_string($xml);
        $schedule = ShiftHoursFactory::make($xml);

        $this->assertInstanceOf(ShiftHours::class, $schedule);
        $this->assertEquals('08:00 AM', $schedule->getOpeningHour());
        $this->assertEquals('08:00 PM', $schedule->getClosingHour());
    }

    public function testItReturnsAJsonRepresentation(): void
    {
        $shiftHours = new ShiftHours('08:00 AM', '08:00 PM');
        $workingSchedule = new WorkingSchedule('monday', $shiftHours);
        $expectedResult = [
            'day' => 'monday',
            'shiftHours' => [
                'openingHour' => '08:00 AM',
                'closingHour' => '08:00 PM',
            ],
        ];

        $this->assertSame($expectedResult, Json::decode(Json::encode($workingSchedule)));
    }
}

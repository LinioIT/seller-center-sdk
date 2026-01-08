<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model\Warehouse;

use Linio\SellerCenter\Contract\CollectionInterface;

class WorkingSchedules implements CollectionInterface
{
    /**
     * @var WorkingSchedule[]
     */
    protected $collection = [];

    public function all(): array
    {
        return $this->collection;
    }

    public function add(WorkingSchedule $workingSchedule): void
    {
        $this->collection[$workingSchedule->getDay()] = $workingSchedule;
    }
}

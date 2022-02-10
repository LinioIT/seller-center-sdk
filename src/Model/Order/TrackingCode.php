<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model\Order;

use JsonSerializable;
use stdClass;

class TrackingCode implements JsonSerializable
{
    /**
     * @var string
     */
    protected $dispatchId;

    /**
     * @var string
     */
    protected $trackingNumber;

    public function __construct(string $dispatchId, string $trackingNumber)
    {
        $this->dispatchId = $dispatchId;
        $this->trackingNumber = $trackingNumber;
    }

    public function getDispatchId(): string
    {
        return $this->dispatchId;
    }

    public function getTrackingNumber(): string
    {
        return $this->trackingNumber;
    }

    public function jsonSerialize(): stdClass
    {
        $serialized = new stdClass();
        $serialized->dispatchId = $this->dispatchId;
        $serialized->trackingNumber = $this->trackingNumber;

        return $serialized;
    }
}

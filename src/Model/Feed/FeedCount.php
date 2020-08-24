<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model\Feed;

use JsonSerializable;
use stdClass;

class FeedCount implements JsonSerializable
{
    /**
     * @var int
     */
    protected $total;

    /**
     * @var int
     */
    protected $queued;

    /**
     * @var int
     */
    protected $processing;

    /**
     * @var int
     */
    protected $finished;

    /**
     * @var int
     */
    protected $canceled;

    public function __construct(
        int $total,
        int $queued,
        int $processing,
        int $finished,
        int $canceled
    ) {
        $this->total = $total;
        $this->queued = $queued;
        $this->processing = $processing;
        $this->finished = $finished;
        $this->canceled = $canceled;
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function getQueued(): int
    {
        return $this->queued;
    }

    public function getProcessing(): int
    {
        return $this->processing;
    }

    public function getFinished(): int
    {
        return $this->finished;
    }

    public function getCanceled(): int
    {
        return $this->canceled;
    }

    public function jsonSerialize(): stdClass
    {
        $serialized = new stdClass();
        $serialized->total = $this->total;
        $serialized->queued = $this->queued;
        $serialized->processing = $this->processing;
        $serialized->finished = $this->finished;
        $serialized->canceled = $this->canceled;

        return $serialized;
    }
}

<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model\Feed;

use JsonSerializable;
use stdClass;

class FailureReports implements JsonSerializable
{
    /**
     * @var string
     */
    protected $mimeType;

    /**
     * @var string
     */
    protected $file;

    public function __construct(string $mimeType, string $file)
    {
        $this->mimeType = $mimeType;
        $this->file = $file;
    }

    public function getFile(): string
    {
        return $this->file;
    }

    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    public function jsonSerialize(): stdClass
    {
        $serialized = new stdClass();
        $serialized->mimeType = $this->mimeType;
        $serialized->file = $this->file;

        return $serialized;
    }
}

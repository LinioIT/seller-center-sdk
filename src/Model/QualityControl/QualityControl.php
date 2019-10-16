<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model\QualityControl;

use JsonSerializable;
use Linio\SellerCenter\Exception\EmptyArgumentException;
use stdClass;

class QualityControl implements JsonSerializable
{
    /**
     * @var string
     */
    protected $sellerSku;

    /**
     * @var string
     */
    protected $status;

    /**
     * @var bool|null
     */
    protected $dataChanged;

    /**
     * @var string|null
     */
    protected $reason;

    public function __construct(string $sellerSku, string $status, ?bool $dataChanged = null, ?string $reason = null)
    {
        if (empty($sellerSku)) {
            throw new EmptyArgumentException('SellerSku');
        }

        if (empty($status)) {
            throw new EmptyArgumentException('Status');
        }

        $this->sellerSku = $sellerSku;
        $this->status = $status;
        $this->dataChanged = $dataChanged;
        $this->reason = $reason;
    }

    public function getSellerSku(): string
    {
        return $this->sellerSku;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getDataChanged(): ?bool
    {
        return $this->dataChanged;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function jsonSerialize(): stdClass
    {
        $serialized = new stdClass();
        $serialized->sellerSku = $this->sellerSku;
        $serialized->status = $this->status;
        $serialized->dataChanged = $this->dataChanged;
        $serialized->reason = $this->reason;

        return $serialized;
    }
}

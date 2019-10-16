<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model\QualityControl;

use Linio\SellerCenter\Contract\CollectionInterface;

class QualityControls implements CollectionInterface
{
    /**
     * @var QualityControl[]
     */
    protected $collection = [];

    public function findBySellerSku(string $SellerSku): ?QualityControl
    {
        if (!key_exists($SellerSku, $this->collection)) {
            return null;
        }

        return $this->collection[$SellerSku];
    }

    public function searchByStatus(string $status): array
    {
        $result = [];

        foreach ($this->collection as $qualityControl) {
            if ($qualityControl->getStatus() == $status) {
                $result[] = $qualityControl;
            }
        }

        return $result;
    }

    public function all(): array
    {
        return $this->collection;
    }

    public function add(QualityControl $qualityControl): void
    {
        $this->collection[$qualityControl->getSellerSku()] = $qualityControl;
    }
}

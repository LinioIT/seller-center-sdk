<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Contract;

interface SuccessResponse
{
    public function getBaseData(): string;

    public function getDetailData(): string;
}

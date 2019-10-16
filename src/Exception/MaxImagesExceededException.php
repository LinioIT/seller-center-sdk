<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Exception;

use OverflowException;

class MaxImagesExceededException extends OverflowException
{
    public function __construct(int $max)
    {
        $message = sprintf('Only %s are supported into the collection.', $max);

        parent::__construct($message);
    }
}

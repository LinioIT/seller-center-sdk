<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Exception;

use InvalidArgumentException;

class EmptyArgumentException extends InvalidArgumentException
{
    public function __construct(string $parameter)
    {
        $message = sprintf('The parameter %s should not be null.', $parameter);

        parent::__construct($message);
    }
}

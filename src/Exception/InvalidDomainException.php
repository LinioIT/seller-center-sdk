<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Exception;

class InvalidDomainException extends \InvalidArgumentException
{
    public function __construct(string $parameter)
    {
        $message = sprintf('The parameter %s is invalid.', $parameter);

        parent::__construct($message);
    }
}

<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Exception;

use RuntimeException;

class InvalidApiKeyException extends RuntimeException
{
    public function __construct()
    {
        $message = 'The API KEY cannot be null.';

        parent::__construct($message);
    }
}

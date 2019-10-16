<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Exception;

use RuntimeException;

class InvalidXmlStructureException extends RuntimeException
{
    public function __construct(string $model, string $tag)
    {
        $message = sprintf('The xml structure is not valid for a %s. The property %s should exist.', $model, $tag);

        parent::__construct($message);
    }
}

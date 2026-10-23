<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Exception;

class InvalidUrlException extends \RuntimeException
{
    public function __construct(string $url)
    {
        $message = sprintf('The url \'%s\' is not valid.', $url);

        parent::__construct($message);
    }
}

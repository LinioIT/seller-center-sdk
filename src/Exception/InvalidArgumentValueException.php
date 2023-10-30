<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Exception;

use InvalidArgumentException;

class InvalidArgumentValueException extends InvalidArgumentException
{
    /**
     * @param string[] $references
     */
    public function __construct(string $parameter, array $references = [])
    {
        $reference = '';

        if (!empty($references)) {
            $reference = sprintf(
                ' , use a valid value like: %s',
                implode(', ', $references)
            );
        }

        $message = sprintf(
            'The parameter %s is invalid%s.',
            $parameter,
            $reference
        );

        parent::__construct($message);
    }
}

<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Exception;

use Linio\Component\Util\Json;
use RuntimeException;

class ErrorJsonResponseException extends RuntimeException
{
    /**
     * @var string
     */
    protected $action;

    /**
     * @var string
     */
    protected $type;

    /**
     * @param mixed[] $error
     */
    public function __construct(array $error)
    {
        $errorsMessage = Json::encode($error);
        parent::__construct($errorsMessage);
    }
}

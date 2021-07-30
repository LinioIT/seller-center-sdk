<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Exception;

use RuntimeException;
use SimpleXMLElement;

class ErrorResponseException extends RuntimeException
{
    /**
     * @var string
     */
    protected $action;

    /**
     * @var string
     */
    protected $type;

    public function __construct(SimpleXMLElement $error)
    {
        $message = (string) $error->Head->ErrorMessage ?? null;
        $code = (int) $error->Head->ErrorCode ?? null;
        $this->type = (string) $error->Head->ErrorType ?? null;
        $this->action = (string) $error->Head->RequestAction ?? null;

        parent::__construct($message, $code, null);
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getAction(): string
    {
        return $this->action;
    }
}

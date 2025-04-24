<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Exception;

class ErrorResponseException extends \RuntimeException
{
    /**
     * @var string
     */
    protected $action;

    /**
     * @var string
     */
    protected $type;

    public function __construct(\SimpleXMLElement $error)
    {
        $message = (string) ($error->Head->ErrorMessage ?? '');
        $code = (int) ($error->Head->ErrorCode ?? 0);
        $this->type = (string) ($error->Head->ErrorType ?? '');
        $this->action = (string) ($error->Head->RequestAction ?? '');

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

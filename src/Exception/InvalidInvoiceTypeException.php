<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Exception;

use Linio\SellerCenter\Model\Document\InvoiceDocument;

class InvalidInvoiceTypeException extends InvalidArgumentValueException
{
    const INVOICE_TYPE_FIELD = 'invoice type';

    public function __construct()
    {
        parent::__construct(self::INVOICE_TYPE_FIELD, InvoiceDocument::INVOICE_TYPES);
    }
}

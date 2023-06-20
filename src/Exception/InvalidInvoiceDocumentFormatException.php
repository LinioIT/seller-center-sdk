<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Exception;

use Linio\SellerCenter\Model\Document\InvoiceDocument;

class InvalidInvoiceDocumentFormatException extends InvalidArgumentValueException
{
    const INVOICE_DOC_FORMAT_FIELD = 'invoice document format';

    public function __construct()
    {
        parent::__construct(self::INVOICE_DOC_FORMAT_FIELD, InvoiceDocument::INVOICE_DOCUMENT_FORMATS);
    }
}

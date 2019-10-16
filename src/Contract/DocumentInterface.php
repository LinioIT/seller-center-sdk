<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Contract;

interface DocumentInterface
{
    public const DOCUMENT_TYPES = [
        'invoice',
        'exportInvoice',
        'shippingLabel',
        'shippingParcel',
        'carrierManifest',
        'serialNumber',
    ];
}

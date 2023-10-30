<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model\Order;

use DateTime;
use JsonSerializable;
use Linio\SellerCenter\Contract\BusinessUnitOperatorCodes;
use Linio\SellerCenter\Exception\InvalidInvoiceDocumentFormatException;
use Linio\SellerCenter\Exception\InvalidInvoiceTypeException;
use Linio\SellerCenter\Exception\InvalidOperatorCodeException;
use stdClass;

class InvoiceDocument implements JsonSerializable
{
    const INVOICE_TYPES = [
        'BOLETA',
        'NOTA_DE_CREDITO',
        'FACTURA',
    ];

    const INVOICE_DOCUMENT_FORMATS = [
        'pdf',
    ];

    /**
     * @var OrderItems
     */
    private $orderItems;

    /**
     * @var string
     */
    private $invoiceNumber;

    /**
     * @var DateTime
     */
    private $invoiceDate;

    /**
     * @var string
     */
    private $invoiceType;

    /**
     * @var string
     */
    private $operatorCode;

    /**
     * @var string
     */
    private $invoiceDocumentFormat;

    /**
     * @var string
     */
    private $invoiceDocumentBase64;

    public function __construct(
        string $invoiceNumber,
        DateTime $invoiceDate,
        string $invoiceType,
        string $operatorCode,
        string $invoiceDocumentBase64,
        OrderItems $orderItems,
        ?string $invoiceDocumentFormat = 'pdf'
    ) {
        $invoiceType = strtoupper($invoiceType);
        if (!in_array($invoiceType, self::INVOICE_TYPES)) {
            throw new InvalidInvoiceTypeException();
        }

        $invoiceDocumentFormat = strtolower($invoiceDocumentFormat);
        if (!in_array($invoiceDocumentFormat, self::INVOICE_DOCUMENT_FORMATS)) {
            throw new InvalidInvoiceDocumentFormatException();
        }

        $operatorCode = strtolower($operatorCode);
        if (!in_array($operatorCode, BusinessUnitOperatorCodes::COUNTRY_OPERATOR)) {
            throw new InvalidOperatorCodeException();
        }

        $this->orderItems = $orderItems;
        $this->invoiceNumber = $invoiceNumber;
        $this->invoiceDate = $invoiceDate;
        $this->invoiceType = $invoiceType;
        $this->operatorCode = $operatorCode;
        $this->invoiceDocumentFormat = $invoiceDocumentFormat;
        $this->invoiceDocumentBase64 = $invoiceDocumentBase64;
    }

    public function getOrderItems(): OrderItems
    {
        return $this->orderItems;
    }

    public function getInvoiceNumber(): string
    {
        return $this->invoiceNumber;
    }

    public function getInvoiceDate(): DateTime
    {
        return $this->invoiceDate;
    }

    public function getInvoiceType(): string
    {
        return $this->invoiceType;
    }

    public function getOperatorCode(): string
    {
        return $this->operatorCode;
    }

    public function getInvoiceDocumentFormat(): string
    {
        return $this->invoiceDocumentFormat;
    }

    public function getInvoiceDocumentBase64(): string
    {
        return $this->invoiceDocumentBase64;
    }

    public function jsonSerialize(): stdClass
    {
        $serialized = new stdClass();

        foreach ($this->orderItems->all() as $item) {
            $orderItems[] = $item->getOrderItemId();
        }

        $serialized->orderItemIds = $orderItems ?? [];
        $serialized->invoiceNumber = $this->invoiceNumber;
        $serialized->invoiceDate = $this->invoiceDate->format('Y-m-d');
        $serialized->invoiceType = $this->invoiceType;
        $serialized->operatorCode = strtoupper($this->operatorCode);
        $serialized->invoiceDocumentFormat = $this->invoiceDocumentFormat;
        $serialized->invoiceDocument = $this->invoiceDocumentBase64;

        return $serialized;
    }
}

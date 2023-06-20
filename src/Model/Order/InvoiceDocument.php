<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model\Document;

use DateTime;
use JsonSerializable;
use Linio\SellerCenter\Contract\BusinessUnitOperatorCodes;
use Linio\SellerCenter\Exception\InvalidInvoiceDocumentFormatException;
use Linio\SellerCenter\Exception\InvalidInvoiceTypeException;
use Linio\SellerCenter\Exception\InvalidOperatorCodeException;
use Linio\SellerCenter\Model\Order\OrderItems;
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

        $operatorCode = strtoupper($operatorCode);
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

    public function setOrdersItems(OrderItems $orderItems): void
    {
        $this->orderItems = $orderItems;
    }

    public function setInvoiceNumber(string $invoiceNumber): void
    {
        $this->invoiceNumber = $invoiceNumber;
    }

    public function setInvoiceDate(DateTime $invoiceDate): void
    {
        $this->invoiceDate = $invoiceDate;
    }

    public function setInvoiceType(string $invoiceType): void
    {
        $this->invoiceType = $invoiceType;
    }

    public function setOperatorCode(string $operatorCode): void
    {
        $this->operatorCode = $operatorCode;
    }

    public function setInvoiceDocumentFormat(string $invoiceDocumentFormat): void
    {
        $this->invoiceDocumentFormat = $invoiceDocumentFormat;
    }

    public function setInvoiceDocumentBase64(string $invoiceDocumentBase64): void
    {
        $this->invoiceDocumentBase64 = $invoiceDocumentBase64;
    }

    public function setOrderItems(OrderItems $orderItems): void
    {
        $this->orderItems = $orderItems;
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

        $serialized->orderItems = $this->orderItems;
        $serialized->invoiceNumber = $this->invoiceNumber;
        $serialized->invoiceDate = $this->invoiceDate;
        $serialized->invoiceType = $this->invoiceType;
        $serialized->operatorCode = $this->operatorCode;
        $serialized->invoiceDocumentFormat = $this->invoiceDocumentFormat;
        $serialized->invoiceDocumentBase64 = $this->invoiceDocumentBase64;

        return $serialized;
    }
}

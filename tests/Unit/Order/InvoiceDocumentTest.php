<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model;

use DateTime;
use Linio\Component\Util\Json;
use Linio\SellerCenter\Exception\InvalidInvoiceDocumentFormatException;
use Linio\SellerCenter\Exception\InvalidInvoiceTypeException;
use Linio\SellerCenter\Exception\InvalidOperatorCodeException;
use Linio\SellerCenter\LinioTestCase;
use Linio\SellerCenter\Model\Order\InvoiceDocument;
use Linio\SellerCenter\Model\Order\OrderItem;
use Linio\SellerCenter\Model\Order\OrderItems;

class InvoiceDocumentTest extends LinioTestCase
{
    /**
     * @var InvoiceDocument
     */
    private $invoiceDocument;

    /**
     * @var string
     */
    private $invoiceNumber = '123123';

    /**
     * @var DateTime
     */
    private $invoiceDate;

    /**
     * @var string
     */
    private $invoiceType = 'BOLETA';

    /**
     * @var string
     */
    private $operatorCode = 'facl';

    /**
     * @var string
     */
    private $invoiceDocumentBase64 = 'qwertyuiopasdfghjklzxcvbnm';

    /**
     * @var string
     */
    private $invoiceDocumentFormat = 'pdf';

    /**
     * @var OrderItems
     */
    private $orderItems;

    /**
     * @var OrderItem
     */
    private $orderItem;

    protected function setUp(): void
    {
        $this->invoiceDate = new DateTime('2023/06/15');
        $orderItems = new OrderItems();
        $this->orderItem = OrderItem::fromStatus(21, 123123, '123123', 'packageID123');
        $orderItems->add($this->orderItem);
        $this->orderItems = $orderItems;

        $this->invoiceDocument = new InvoiceDocument(
            $this->invoiceNumber,
            $this->invoiceDate,
            $this->invoiceType,
            $this->operatorCode,
            $this->invoiceDocumentBase64,
            $this->orderItems,
            $this->invoiceDocumentFormat
        );
    }

    public function testInvoiceDocumentSettersAndGetters(): void
    {
        $this->assertEquals($this->invoiceDocument->getInvoiceNumber(), $this->invoiceNumber);
        $this->assertEquals($this->invoiceDocument->getInvoiceDate(), $this->invoiceDate);
        $this->assertEquals($this->invoiceDocument->getInvoiceType(), $this->invoiceType);
        $this->assertEquals($this->invoiceDocument->getOperatorCode(), $this->operatorCode);
        $this->assertEquals($this->invoiceDocument->getInvoiceDocumentBase64(), $this->invoiceDocumentBase64);
        $this->assertEquals($this->invoiceDocument->getOrderItems(), $this->orderItems);
        $this->assertEquals($this->invoiceDocument->getInvoiceDocumentFormat(), $this->invoiceDocumentFormat);
    }

    public function testInvoiceDocumentJsonSerialize(): void
    {
        $result = $this->invoiceDocument->jsonSerialize();

        $excepted = [
            'orderItemIds' => [$this->orderItem->getOrderItemId()],
            'invoiceNumber' => $this->invoiceNumber,
            'invoiceDate' => $this->invoiceDate->format('Y-m-d'),
            'invoiceType' => $this->invoiceType,
            'operatorCode' => strtoupper($this->operatorCode),
            'invoiceDocumentFormat' => $this->invoiceDocumentFormat,
            'invoiceDocument' => $this->invoiceDocumentBase64,
        ];

        $this->assertEquals(Json::encode($excepted), Json::encode($result));
    }

    /**
     * @dataProvider exceptionProviders
     */
    public function testInvoiceDocumentInvalidArgumentException(
        string $excepcion,
        string $messageException,
        string $invoiceNumber,
        string $invoiceType,
        string $operatorCode,
        string $invoiceDocumentBase64,
        string $invoiceDocumentFormat
    ): void {
        $this->expectException($excepcion);
        $this->expectExceptionMessage($messageException);
        new InvoiceDocument(
            $invoiceNumber,
            $this->invoiceDate,
            $invoiceType,
            $operatorCode,
            $invoiceDocumentBase64,
            $this->orderItems,
            $invoiceDocumentFormat
        );
    }

    /**
     * @return mixed[]
     */
    public function exceptionProviders(): array
    {
        return [
            'InvalidInvoiceTypeException' => [
                'exception' => InvalidInvoiceTypeException::class,
                'messageException' => 'The parameter invoice type is invalid , use a valid value like: BOLETA, NOTA_DE_CREDITO, FACTURA.',
                'invoiceNumber' => $this->invoiceNumber,
                'invoiceType' => 'nonexistent type',
                'operatorCode' => $this->operatorCode,
                'invoiceDocumentBase64' => $this->invoiceDocumentBase64,
                'invoiceDocumentFormat' => $this->invoiceDocumentFormat,
            ],
            'InvalidInvoiceDocumentFormatException' => [
                'exception' => InvalidInvoiceDocumentFormatException::class,
                'messageException' => 'The parameter invoice document format is invalid , use a valid value like: pdf.',
                'invoiceNumber' => $this->invoiceNumber,
                'invoiceType' => $this->invoiceType,
                'operatorCode' => $this->operatorCode,
                'invoiceDocumentBase64' => $this->invoiceDocumentBase64,
                'invoiceDocumentFormat' => 'xml',
            ],
            'InvalidOperatorCodeException' => [
                'exception' => InvalidOperatorCodeException::class,
                'messageException' => 'The parameter operator code is invalid , use a valid value like: facl, fape, famx, faco.',
                'invoiceNumber' => $this->invoiceNumber,
                'invoiceType' => $this->invoiceType,
                'operatorCode' => 'fafa',
                'invoiceDocumentBase64' => $this->invoiceDocumentBase64,
                'invoiceDocumentFormat' => $this->invoiceDocumentFormat,
            ],
        ];
    }
}

<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model\Order;

use DateTimeImmutable;
use JsonSerializable;
use stdClass;

class OrderItem implements JsonSerializable
{
    /**
     * @var int
     */
    protected $orderItemId;

    /**
     * @var int|null
     */
    protected $shopId;

    /**
     * @var int|null
     */
    protected $orderId;

    /**
     * @var string|null
     */
    protected $name;

    /**
     * @var string|null
     */
    protected $sku;

    /**
     * @var string|null
     */
    protected $variation;

    /**
     * @var string|null
     */
    protected $shopSku;

    /**
     * @var string|null
     */
    protected $shippingType;

    /**
     * @var float|null
     */
    protected $itemPrice;

    /**
     * @var float|null
     */
    protected $paidPrice;

    /**
     * @var string|null
     */
    protected $currency;

    /**
     * @var float|null
     */
    protected $walletCredits;

    /**
     * @var float|null
     */
    protected $taxAmount;

    /**
     * @var float|null
     */
    protected $codCollectableAmount;

    /**
     * @var float|null
     */
    protected $shippingAmount;

    /**
     * @var float|null
     */
    protected $shippingServiceCost;

    /**
     * @var int|null
     */
    protected $voucherAmount;

    /**
     * @var string|null
     */
    protected $voucherCode;

    /**
     * @var string|null
     */
    protected $status;

    /**
     * @var bool|null
     */
    protected $isProcessable;

    /**
     * @var string|null
     */
    protected $shipmentProvider;

    /**
     * @var bool|null
     */
    protected $isDigital;

    /**
     * @var string|null
     */
    protected $digitalDeliveryInfo;

    /**
     * @var string|null
     */
    protected $trackingCode;

    /**
     * @var string|null
     */
    protected $trackingCodePre;

    /**
     * @var string|null
     */
    protected $reason;

    /**
     * @var string|null
     */
    protected $reasonDetail;

    /**
     * @var int|null
     */
    protected $purchaseOrderId;

    /**
     * @var string|null
     */
    protected $purchaseOrderNumber;

    /**
     * @var string|null
     */
    protected $packageId;

    /**
     * @var DateTimeImmutable|null
     */
    protected $promisedShippingTime;

    /**
     * @var mixed[]|null
     */
    protected $extraAttributes;

    /**
     * @var string|null
     */
    protected $shippingProviderType;

    /**
     * @var DateTimeImmutable|null
     */
    protected $createdAt;

    /**
     * @var DateTimeImmutable|null
     */
    protected $updatedAt;

    /**
     * @var string|null
     */
    protected $returnStatus;

    /**
     * @var string|null
     */
    protected $salesType;

    /**
     * @param mixed[]|null $extraAttributes
     */
    final public static function fromOrderItem(
        int $orderItemId,
        int $shopId,
        int $orderId,
        string $name,
        string $sku,
        string $variation,
        string $shopSku,
        string $shippingType,
        float $itemPrice,
        float $paidPrice,
        string $currency,
        float $walletCredits,
        float $taxAmount,
        ?float $codCollectableAmount,
        float $shippingAmount,
        float $shippingServiceCost,
        int $voucherAmount,
        ?string $voucherCode,
        string $status,
        bool $isProcessable,
        string $shipmentProvider,
        bool $isDigital,
        ?string $digitalDeliveryInfo,
        ?string $trackingCode,
        ?string $trackingCodePre,
        ?string $reason,
        ?string $reasonDetail,
        ?int $purchaseOrderId,
        ?string $purchaseOrderNumber,
        ?string $packageId,
        ?DateTimeImmutable $promisedShippingTime,
        ?array $extraAttributes,
        string $shippingProviderType,
        ?DateTimeImmutable $createdAt,
        ?DateTimeImmutable $updatedAt,
        ?string $returnStatus,
        ?string $salesType = null
    ): OrderItem {
        $orderItem = new self();

        $orderItem->orderItemId = $orderItemId;
        $orderItem->shopId = $shopId;
        $orderItem->orderId = $orderId;
        $orderItem->name = $name;
        $orderItem->sku = $sku;
        $orderItem->variation = $variation;
        $orderItem->shopSku = $shopSku;
        $orderItem->shippingType = $shippingType;
        $orderItem->itemPrice = $itemPrice;
        $orderItem->paidPrice = $paidPrice;
        $orderItem->currency = $currency;
        $orderItem->walletCredits = $walletCredits;
        $orderItem->taxAmount = $taxAmount;
        $orderItem->codCollectableAmount = $codCollectableAmount;
        $orderItem->shippingAmount = $shippingAmount;
        $orderItem->shippingServiceCost = $shippingServiceCost;
        $orderItem->voucherAmount = $voucherAmount;
        $orderItem->voucherCode = $voucherCode;
        $orderItem->status = $status;
        $orderItem->isProcessable = $isProcessable;
        $orderItem->shipmentProvider = $shipmentProvider;
        $orderItem->isDigital = $isDigital;
        $orderItem->digitalDeliveryInfo = $digitalDeliveryInfo;
        $orderItem->trackingCode = $trackingCode;
        $orderItem->trackingCodePre = $trackingCodePre;
        $orderItem->reason = $reason;
        $orderItem->reasonDetail = $reasonDetail;
        $orderItem->purchaseOrderId = $purchaseOrderId;
        $orderItem->purchaseOrderNumber = $purchaseOrderNumber;
        $orderItem->packageId = $packageId;
        $orderItem->promisedShippingTime = $promisedShippingTime;
        $orderItem->extraAttributes = $extraAttributes;
        $orderItem->shippingProviderType = $shippingProviderType;
        $orderItem->createdAt = $createdAt;
        $orderItem->updatedAt = $updatedAt;
        $orderItem->returnStatus = $returnStatus;
        $orderItem->salesType = $salesType;

        return $orderItem;
    }

    public static function fromStatus(
        int $orderItemId,
        int $purchaseOrderId,
        string $purchaseOrderNumber,
        ?string $packageId
    ): OrderItem {
        $orderItem = new self();

        $orderItem->purchaseOrderId = $purchaseOrderId;
        $orderItem->purchaseOrderNumber = $purchaseOrderNumber;
        $orderItem->orderItemId = $orderItemId;
        $orderItem->packageId = $packageId;

        return $orderItem;
    }

    public function getOrderItemId(): int
    {
        return $this->orderItemId;
    }

    public function getShopId(): ?int
    {
        return $this->shopId;
    }

    public function getOrderId(): ?int
    {
        return $this->orderId;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getSku(): ?string
    {
        return $this->sku;
    }

    public function getVariation(): ?string
    {
        return $this->variation;
    }

    public function getShopSku(): ?string
    {
        return $this->shopSku;
    }

    public function getShippingType(): ?string
    {
        return $this->shippingType;
    }

    public function getItemPrice(): ?float
    {
        return $this->itemPrice;
    }

    public function getPaidPrice(): ?float
    {
        return $this->paidPrice;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function getWalletCredits(): ?float
    {
        return $this->walletCredits;
    }

    public function getTaxAmount(): ?float
    {
        return $this->taxAmount;
    }

    public function getCodCollectableAmount(): ?float
    {
        return !empty($this->codCollectableAmount) ? $this->codCollectableAmount : null;
    }

    public function getShippingAmount(): ?float
    {
        return $this->shippingAmount;
    }

    public function getShippingServiceCost(): ?float
    {
        return $this->shippingServiceCost;
    }

    public function getVoucherAmount(): ?int
    {
        return $this->voucherAmount;
    }

    public function getVoucherCode(): ?string
    {
        return !empty($this->voucherCode) ? $this->voucherCode : null;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function getIsProcessable(): ?bool
    {
        return $this->isProcessable;
    }

    public function getShipmentProvider(): ?string
    {
        return $this->shipmentProvider;
    }

    public function getIsDigital(): ?bool
    {
        return $this->isDigital;
    }

    public function getDigitalDeliveryInfo(): ?string
    {
        return !empty($this->digitalDeliveryInfo) ? $this->digitalDeliveryInfo : null;
    }

    public function getTrackingCode(): ?string
    {
        return $this->trackingCode;
    }

    public function getTrackingCodePre(): ?string
    {
        return !empty($this->trackingCodePre) ? $this->trackingCodePre : null;
    }

    public function getReason(): ?string
    {
        return !empty($this->reason) ? $this->reason : null;
    }

    public function getReasonDetail(): ?string
    {
        return !empty($this->reasonDetail) ? $this->reasonDetail : null;
    }

    public function getPurchaseOrderId(): ?int
    {
        return $this->purchaseOrderId;
    }

    public function getPurchaseOrderNumber(): ?string
    {
        return !empty($this->purchaseOrderNumber) ? $this->purchaseOrderNumber : null;
    }

    public function getPackageId(): ?string
    {
        return $this->packageId;
    }

    public function getPromisedShippingTime(): ?DateTimeImmutable
    {
        return $this->promisedShippingTime;
    }

    /**
     * @return mixed[]|null
     */
    public function getExtraAttributes(): ?array
    {
        return !empty($this->extraAttributes) ? $this->extraAttributes : null;
    }

    public function getShippingProviderType(): ?string
    {
        return $this->shippingProviderType;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return !empty($this->createdAt) ? $this->createdAt : null;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return !empty($this->updatedAt) ? $this->updatedAt : null;
    }

    public function getReturnStatus(): ?string
    {
        return !empty($this->returnStatus) ? $this->returnStatus : null;
    }

    public function getSalesType(): ?string
    {
        return !empty($this->salesType) ? $this->salesType : null;
    }

    public function jsonSerialize(): stdClass
    {
        $serialized = new stdClass();
        $serialized->orderItemId = $this->orderItemId;
        $serialized->shopId = $this->shopId;
        $serialized->orderId = $this->orderId;
        $serialized->name = $this->name;
        $serialized->sku = $this->sku;
        $serialized->variation = $this->variation;
        $serialized->shopSku = $this->shopSku;
        $serialized->shippingType = $this->shippingType;
        $serialized->itemPrice = $this->itemPrice;
        $serialized->paidPrice = $this->paidPrice;
        $serialized->currency = $this->currency;
        $serialized->walletCredits = $this->walletCredits;
        $serialized->taxAmount = $this->taxAmount;
        $serialized->codCollectableAmount = $this->codCollectableAmount;
        $serialized->shippingAmount = $this->shippingAmount;
        $serialized->shippingServiceCost = $this->shippingServiceCost;
        $serialized->voucherAmount = $this->voucherAmount;
        $serialized->voucherCode = $this->voucherCode;
        $serialized->status = $this->status;
        $serialized->isProcessable = $this->isProcessable;
        $serialized->shipmentProvider = $this->shipmentProvider;
        $serialized->isDigital = $this->isDigital;
        $serialized->digitalDeliveryInfo = $this->digitalDeliveryInfo;
        $serialized->trackingCode = $this->trackingCode;
        $serialized->trackingCodePre = $this->trackingCodePre;
        $serialized->reason = $this->reason;
        $serialized->reasonDetail = $this->reasonDetail;
        $serialized->purchaseOrderId = $this->purchaseOrderId;
        $serialized->purchaseOrderNumber = $this->purchaseOrderNumber;
        $serialized->packageId = $this->packageId;
        $serialized->promisedShippingTime = $this->promisedShippingTime;
        $serialized->extraAttributes = $this->extraAttributes;
        $serialized->shippingProviderType = $this->shippingProviderType;
        $serialized->createdAt = $this->createdAt;
        $serialized->updatedAt = $this->updatedAt;
        $serialized->returnStatus = $this->returnStatus;
        $serialized->salesType = $this->salesType;

        return $serialized;
    }
}

<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Model\Order;

use DateTimeInterface;
use JsonSerializable;
use stdClass;

class Order implements JsonSerializable
{
    /**
     * @var int
     */
    protected $orderId;

    /**
     * @var string|null
     */
    protected $customerFirstName;

    /**
     * @var string|null
     */
    protected $customerLastName;

    /**
     * @var int
     */
    protected $orderNumber;

    /**
     * @var string|null
     */
    protected $paymentMethod;

    /**
     * @var string|null
     */
    protected $remarks;

    /**
     * @var string|null
     */
    protected $deliveryInfo;

    /**
     * @var float|null
     */
    protected $price;

    /**
     * @var bool|null
     */
    protected $giftOption;

    /**
     * @var string|null
     */
    protected $giftMessage;

    /**
     * @var string|null
     */
    protected $voucherCode;

    /**
     * @var DateTimeInterface|null
     */
    protected $createdAt;

    /**
     * @var DateTimeInterface|null
     */
    protected $updatedAt;

    /**
     * @var DateTimeInterface|null
     */
    protected $addressUpdatedAt;

    /**
     * @var Address|null
     */
    protected $addressBilling;

    /**
     * @var Address|null
     */
    protected $addressShipping;

    /**
     * @var string|null
     */
    protected $nationalRegistrationNumber;

    /**
     * @var int|null
     */
    protected $itemsCount;

    /**
     * @var DateTimeInterface|null
     */
    protected $promisedShippingTime;

    /**
     * @var string|null
     */
    protected $extraAttributes;

    /**
     * @var array|null
     */
    protected $statuses;

    /**
     * @var OrderItems|null
     */
    protected $orderItems;

    /**
     * @return static
     */
    public static function fromData(
        int $orderId,
        int $orderNumber,
        ?string $customerFirstName,
        ?string $customerLastName,
        string $paymentMethod,
        string $remarks,
        string $deliveryInfo,
        float $price,
        bool $giftOption,
        string $giftMessage,
        string $voucherCode,
        ?DateTimeInterface $createdAt,
        ?DateTimeInterface $updatedAt,
        ?DateTimeInterface $addressUpdatedAt,
        Address $addressBilling,
        Address $addressShipping,
        string $nationalRegistrationNumber,
        int $itemsCount,
        ?DateTimeInterface $promisedShippingTime,
        ?string $extraAttributes,
        array $statuses
    ): Order {
        $order = new static();

        $order->orderId = $orderId;
        $order->orderNumber = $orderNumber;
        $order->customerFirstName = $customerFirstName;
        $order->customerLastName = $customerLastName;
        $order->paymentMethod = $paymentMethod;
        $order->remarks = $remarks;
        $order->deliveryInfo = $deliveryInfo;
        $order->price = $price;
        $order->giftOption = $giftOption;
        $order->giftMessage = $giftMessage;
        $order->voucherCode = $voucherCode;
        $order->createdAt = $createdAt;
        $order->updatedAt = $updatedAt;
        $order->addressUpdatedAt = $addressUpdatedAt;
        $order->addressBilling = $addressBilling;
        $order->addressShipping = $addressShipping;
        $order->nationalRegistrationNumber = $nationalRegistrationNumber;
        $order->itemsCount = $itemsCount;
        $order->promisedShippingTime = $promisedShippingTime;
        $order->extraAttributes = $extraAttributes;
        $order->statuses = $statuses;

        return $order;
    }

    /**
     * @return static
     */
    public static function fromItems(int $orderId, int $orderNumber, OrderItems $orderItems): Order
    {
        $order = new static();

        $order->orderId = $orderId;
        $order->orderNumber = $orderNumber;
        $order->orderItems = $orderItems;

        return $order;
    }

    public function getOrderId(): int
    {
        return $this->orderId;
    }

    public function getCustomerFirstName(): ?string
    {
        return $this->customerFirstName;
    }

    public function getCustomerLastName(): ?string
    {
        return $this->customerLastName;
    }

    public function getOrderNumber(): int
    {
        return $this->orderNumber;
    }

    public function getPaymentMethod(): ?string
    {
        return $this->paymentMethod;
    }

    public function getRemarks(): ?string
    {
        return $this->remarks;
    }

    public function getDeliveryInfo(): ?string
    {
        return $this->deliveryInfo;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function getGiftOption(): ?bool
    {
        return $this->giftOption;
    }

    public function getGiftMessage(): ?string
    {
        return $this->giftMessage;
    }

    public function getVoucherCode(): ?string
    {
        return $this->voucherCode;
    }

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function getAddressUpdatedAt(): ?DateTimeInterface
    {
        return $this->addressUpdatedAt;
    }

    public function getAddressBilling(): ?Address
    {
        return $this->addressBilling;
    }

    public function getAddressShipping(): ?Address
    {
        return $this->addressShipping;
    }

    public function getNationalRegistrationNumber(): ?string
    {
        return $this->nationalRegistrationNumber;
    }

    public function getItemsCount(): ?int
    {
        return $this->itemsCount;
    }

    public function getPromisedShippingTime(): ?DateTimeInterface
    {
        return $this->promisedShippingTime;
    }

    public function getExtraAttributes(): ?string
    {
        return $this->extraAttributes;
    }

    public function getStatuses(): ?array
    {
        return $this->statuses;
    }

    public function getOrderItems(): ?OrderItems
    {
        return $this->orderItems;
    }

    public function jsonSerialize(): stdClass
    {
        $serialized = new stdClass();
        $serialized->orderId = $this->orderId;
        $serialized->customerFirstName = $this->customerFirstName;
        $serialized->customerLastName = $this->customerLastName;
        $serialized->orderNumber = $this->orderNumber;
        $serialized->paymentMethod = $this->paymentMethod;
        $serialized->remarks = $this->remarks;
        $serialized->deliveryInfo = $this->deliveryInfo;
        $serialized->price = $this->price;
        $serialized->giftOption = $this->giftOption;
        $serialized->giftMessage = $this->giftMessage;
        $serialized->voucherCode = $this->voucherCode;
        $serialized->createdAt = $this->createdAt;
        $serialized->updatedAt = $this->updatedAt;
        $serialized->addressUpdatedAt = $this->addressUpdatedAt;
        $serialized->addressBilling = $this->addressBilling;
        $serialized->addressShipping = $this->addressShipping;
        $serialized->nationalRegistrationNumber = $this->nationalRegistrationNumber;
        $serialized->itemsCount = $this->itemsCount;
        $serialized->promisedShippingTime = $this->promisedShippingTime;
        $serialized->extraAttributes = $this->extraAttributes;
        $serialized->statuses = $this->statuses;
        $serialized->orderItems = $this->orderItems;

        return $serialized;
    }
}

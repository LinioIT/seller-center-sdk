# Linio Order Manager

## Index

  - [Setting order items imei](#setting-order-items-imei)
    - [setOrderItemsImei](#setorderitemsimei)
      - [Example](#example)
  - [Setting order status](#setting-order-status)
    - [setStatusToPackedByMarketplace](#setstatustopackedbymarketplace)
      - [Example](#example-1)
    - [setStatusToReadyToShip](#setstatustoreadytoship)
      - [Example](#example-2)
  - [Setting order invoice number](#setting-order-invoice-number)
    - [setInvoiceNumber](#setinvoicenumber)
      - [Example](#example-3)

## Setting order items imei

### setOrderItemsImei

This method sets the Imei for the order items in the parameters and return the same order items with the status and in case it fails a message on the order item

| Parameter | Type | Description | Required | Default |
| --------- | :----: | ----------- | :--------: | :-------: |
| `$orderItems` | array | Array of order item to set the imei | Yes | - |

#### Example

```php
$orderItems = $this->sdk->orders()->getOrderItems(1234);

foreach($orderItems as $orderItem){
  $orderItem->setImei("1234567890");
}

$orderItems = $this->sdk->orders()->setOrderItemsImei(
    $orderItems,
);
```
## Setting order status

### setStatusToPackedByMarketplace

This method sets an order item as packed and returns order items as the response.

| Parameter | Type | Description | Required | Default |
| --------- | :----: | ----------- | :--------: | :-------: |
| `$orderItemIds` | array | Array of order item IDs to be set as packed | Yes | - |
| `$deliveryType` | string | One of the following: 'dropship' (The seller will send out the package on his own), 'pickup' (Shop should pick up the item from the seller) or 'send_to_warehouse' (The seller will send the item to the warehouse) | Yes | - |
| `$shippingProvider` | string |Valid shipment provider as looked up via [GetShipmentProviders](https://sellerapi.sellercenter.net/docs/getshipmentproviders) | Yes | - |
| `$trackingNumber` | string | The actual tracking number. A custom one or provided by GetOrderItems call | Yes | - |

#### Example

```php
// Define order items ids to be set status.
$orderItemIds = [1234, 5678];

// Set their status to packed by marketplace with dropshipping modal
$orderItems = $sdk->orders()->setStatusToPackedByMarketplace($orderItems, 'dropship', 'ShippingProvider');
```
--------------

### setStatusToReadyToShip

This method sets an order item as ready to ship and returns the order items as the response.

| Parameter | Type | Description | Required | Default |
| --------- | :----: | ----------- | :--------: | :-------: |
| `$orderItemIds` | array | Array of order item IDs to be set as ready to ship | Yes | - |
| `$deliveryType` | string | One of the following: 'dropship' (The seller will send out the package on his own), 'pickup' (Shop should pick up the item from the seller) or 'send_to_warehouse' (The seller will send the item to the warehouse) | Yes | - |
| `$shippingProvider` | string |Valid shipment provider as looked up via [GetShipmentProviders](https://sellerapi.sellercenter.net/docs/getshipmentproviders) | Yes | - |
| `$trackingNumber` | string | The actual tracking number. A custom one or provided by GetOrderItems call | Yes | - |

#### Example

```php
// Define order items IDs to set their status.
$orderItemIds = [1234, 5678];

// Set their status to ready to ship with dropshipping method
$orderItems = $sdk->orders()->setStatusToReadyToShip($orderItemIds, 'dropship', 'ShippingProvider');
```
--------------

## Setting order invoice number

### setInvoiceNumber

This method sets the invoice number.

| Parameter | Type | Description | Required | Default |
| --------- | :----: | ----------- | :--------: | :-------: |
| `$orderItemId` | int | Identifier of the order item should be updated | Yes | - |
| `$invoiceNumber` | string | The invoice number | Yes | - |

#### Example

```php
// Get items collection from order #1234
$orderItems = $sdk->globalOrders()->getOrderItemId(1234);
$invoiceNumber = '123132465465465465456';

$orderItems = $sdk->globalOrders()->setInvoiceNumber($orderItems, $invoiceNumber);
```
--------------

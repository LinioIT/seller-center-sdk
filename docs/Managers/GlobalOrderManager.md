# Global Order Manager

## Index

  - [Setting order status](#setting-order-status)
    - [setStatusToPackedByMarketplace](#setstatustopackedbymarketplace)
      - [Example](#example)
    - [setStatusToReadyToShip](#setstatustoreadytoship)
      - [Example](#example-1)
  - [Setting order invoice number](#setting-order-invoice-number)
    - [setInvoiceNumber](#setinvoicenumber)
      - [Example](#example-2)
  

## Setting order status

### setStatusToPackedByMarketplace

This method sets an order item as packed and returns order items as the response.

| Parameter | Type | Description | Required | Default |
| --------- | :----: | ----------- | :--------: | :-------: |
| `$orderItemIds` | array | Array of order item IDs to be set as packed | Yes | - |
| `$deliveryType` | string | One of the following: 'dropship' (The seller will send out the package on his own), 'pickup' (Shop should pick up the item from the seller) or 'send_to_warehouse' (The seller will send the item to the warehouse) | Yes | - |

#### Example

```php
// Define order items ids to be set status.
$orderItemIds = [1234, 5678];

// Set their status to packed by marketplace with dropshipping modal
$orderItems = $sdk->globalOrders()->setStatusToPackedByMarketplace($orderItems, 'dropship');
```
--------------

### setStatusToReadyToShip

This method sets an order item as ready to ship and returns the order items as the response.

| Parameter | Type | Description | Required | Default |
| --------- | :----: | ----------- | :--------: | :-------: |
| `$orderItemIds` | array | Array of order item IDs to be set as ready to ship | Yes | - |
| `$deliveryType` | string | One of the following: 'dropship' (The seller will send out the package on his own), 'pickup' (Shop should pick up the item from the seller) or 'send_to_warehouse' (The seller will send the item to the warehouse) | Yes | - |
| `$packageId` | string | The actual package Id. A custom one or provided by GetOrderItems call | No | - |

#### Example

```php
// Define order items IDs to set their status.
$orderItemIds = [1234, 5678];

// Set their status to ready to ship with dropshipping method
$orderItems = $sdk->globalOrders()->setStatusToReadyToShip($orderItemIds, 'dropship', 'packageId');
```
--------------

## Setting order invoice number

### setInvoiceNumber

This method sets the invoice number.

| Parameter | Type | Description | Required | Default |
| --------- | :----: | ----------- | :--------: | :-------: |
| `$orderItemId` | int | Identifier of the order item should be updated | Yes | - |
| `$invoiceNumber` | string | The invoice number | Yes | - |
| `$invoiceDocumentLink` | string | Document URL | No | - |

#### Example

```php
// Get items collection from order #1234
$orderItems = $sdk->globalOrders()->getOrderItemId(1234);
$invoiceNumber = '123132465465465465456';
$invoiceDocumentLink = 'https://fakeInvoice.pdf';

$orderItems = $sdk->globalOrders()->setInvoiceNumber($orderItems, $invoiceNumber, $invoiceDocumentLink);
```
--------------

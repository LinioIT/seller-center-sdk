# Global Order Manager

## Index
- [Global Order Manager](#global-order-manager)
  - [Index](#index)
  - [Getting an order](#getting-an-order)
    - [getOrder](#getorder)
      - [Example](#example)
  - [Getting multiple orders](#getting-multiple-orders)
    - [getOrdersFromParameters](#getordersfromparameters)
      - [Example](#example-1)
    - [getOrdersCreatedBetween](#getorderscreatedbetween)
      - [Example](#example-2)
    - [getOrdersUpdatedBetween](#getordersupdatedbetween)
      - [Example](#example-3)
    - [getOrdersCreatedAfter](#getorderscreatedafter)
      - [Example](#example-4)
    - [getOrdersCreatedBefore](#getorderscreatedbefore)
      - [Example](#example-5)
    - [getOrdersUpdatedAfter](#getordersupdatedafter)
      - [Example](#example-6)
    - [getOrdersUpdatedBefore](#getordersupdatedbefore)
      - [Example](#example-7)
    - [getOrdersWithStatus](#getorderswithstatus)
      - [Example](#example-8)
  - [Getting order items](#getting-order-items)
    - [getOrderItems](#getorderitems)
      - [Example](#example-9)
    - [getMultipleOrderItems](#getmultipleorderitems)
      - [Example](#example-10)
  - [Setting order status](#setting-order-status)
    - [setStatusToPackedByMarketplace](#setstatustopackedbymarketplace)
      - [Example](#example-11)
    - [setStatusToReadyToShip](#setstatustoreadytoship)
      - [Example](#example-12)
    - [setStatusToCanceled](#setstatustocanceled)
      - [Example](#example-13)
  - [Setting order invoice number](#setting-order-invoice-number)
    - [setInvoiceNumber](#setinvoicenumber)
      - [Example](#example-14)
  - [Setting order invoice document](#setting-order-invoice-document)
    - [setInvoiceDocument](#setinvoicedocument)
      - [Example](#example-15)
  
## Getting an order

### getOrder

To retrieve a specific order, it's only needed to specify the id and run the method `getOrder` as follows.

| Parameter | Type | Description | Required | Default |
| --------- | ---- | ----------- | -------- | ------- |
| `$orderId` | int | The ID of the order to retrieve | Yes | - |

#### Example

```php
$order = $sdk->globalOrders()->getOrder($orderId);
```

## Getting multiple orders

There are multiple ways of getting a collection of orders.

### getOrdersFromParameters

This method provides you the possibility to use the most common parameters in one call. 

| Parameter | Type | Description | Required | Default |
| --------- | :----: | ----------- | :--------: | :-------: |
| `$createdAfter` | DateTimeInterface&#124;null | Filters the orders using the field createdAfter and the specified date. | No | - |
| `$createdBefore` | DateTimeInterface&#124;null | Filters the orders using the field createdBefore and the specified date. | No | - |
| `$updatedAfter` | DateTimeInterface&#124;null | Filters the orders using the field updateAfter and the specified date. | No | - |
| `$updatedBefore` | DateTimeInterface&#124;null | Filters the orders using the field updatedBefore and the specified date. | No | - |
| `$status` | string&#124;null | Filters the orders through their status. The possible values are pending, canceled, ready_to_ship, delivered, returned, shipped and failed. | No | - |
| `$limit` | int | The maximum number of orders that could be returned. | No | 1000 |
| `$offset` | int | Number of orders to skip at the beginning of the list. | No | 0 |
| `$sortBy` | string | Allows choosing the sorting column. The possible values are created_at and updated_at. | No | created_at |
| `$sortDirection` | string | Specify the sort type. The possible are values (ASC, DESC). | No | ASC |
  
#### Example

```php
// Get all orders using default parameters
$orders = $sdk->globalOrders()->getOrdersFromParameters();
```

-----------

### getOrdersCreatedBetween

This method returns the orders created between two specified dates.

| Parameter | Type | Description | Required | Default |
| --------- | :----: | ----------- | :--------: | :-------: |
| `$createdAfter` | DateTimeInterface | Filters the orders using the field createdAfter and the specified date. | Yes | - |
| `$createdBefore` | DateTimeInterface | Filters the orders using the field createdBefore and the specified date. | Yes | - |
| `$limit` | int | The maximum number of orders that could be returned. | No | 1000 |
| `$offset` | int | Number of orders to skip at the beginning of the list. | No | 0 |
| `$sortBy` | string | Allows choosing the sorting column. The possible values are created_at and updated_at. | No | created_at |
| `$sortDirection` | string | Specify the sort type. The possible are values (ASC, DESC). | No | ASC |

#### Example

```php
$since = new DateTime('2018/01/01');
$until = new DateTime('2019/01/01');
$limit = 20;
$sortDirection = "DESC";

// Get a maximum of 20 last orders created in 2018
$orders = $sdk->globalOrders()->getOrdersCreatedBetween($since, $until, $limit, null, null, $sortDirection);
```

----------

### getOrdersUpdatedBetween

This method returns the orders between the two specified dates.

| Parameter | Type | Description | Required | Default |
| --------- | :----: | ----------- | :--------: | :-------: |
| `$updatedAfter` | DateTimeInterface | Filters the orders using the field updateAfter and the specified date. | Yes | - |
| `$updatedBefore` | DateTimeInterface&#124;null | Filters the orders using the field updatedBefore and the specified date. | Yes | - |
| `$limit` | int | The maximum number of orders that could be returned. | No | 1000 |
| `$offset` | int | Number of orders to skip at the beginning of the list. | No | 0 |
| `$sortBy` | string | Allows choosing the sorting column. The possible values are created_at and updated_at. | No | created_at |
| `$sortDirection` | string | Specify the sort type. The possible are values (ASC, DESC). | No | ASC |

#### Example

```php
// Get orders updated in the last week.
$since = new DateTime('-1 week');
$until = new DateTime();
$orders = $sdk->globalOrders()->getOrdersUpdatedBetween($since, $until);
```

------------

### getOrdersCreatedAfter

This method returns the orders created after the specified date.

| Parameter | Type | Description | Required | Default |
| --------- | :----: | ----------- | :--------: | :-------: |
| `$createdAfter` | DateTimeInterface | Limits the returned order list to those created after or on a specified date. | Yes | - |
| `$limit` | int | The maximum number of orders that could be returned. | No | 1000 |
| `$offset` | int | Number of orders to skip at the beginning of the list. | No | 0 |
| `$sortBy` | string | Allows choosing the sorting column. The possible values are created_at and updated_at. | No | created_at |
| `$sortDirection` | string | Specify the sort type. The possible are values (ASC, DESC). | No | ASC |

#### Example

```php
$after = new DateTime('-1 month');
$limit = 10;
$sortDirection = 'DESC';

// Get the last ten orders created in the past month
$orders = $sdk->globalOrders()->getOrdersCreatedAfter($after, $limit, null, null, $sortDirection);
```

------------

### getOrdersCreatedBefore

This method returns the orders created before the specified date.

| Parameter | Type | Description | Required | Default |
| --------- | ---- | ----------- | -------- | ------- |
| `$createdBefore` | DateTimeInterface | Limits the returned order list to those created before or on a specified date. | Yes | - |
| `$limit` | int | The maximum number of orders that could be returned. | No | 1000 |
| `$offset` | int | Number of orders to skip at the beginning of the list. | No | 0 |
| `$sortBy` | string | Allows choosing the sorting column. The possible values are created_at and updated_at. | No | created_at |
| `$sortDirection` | string | Specify the sort type. The possible are values (ASC, DESC). | No | ASC |

#### Example

```php
$before = new DateTime('2018/01/01');

// Get orders created before 2018.
$orders = $sdk->globalOrders()->getOrdersCreatedBefore($before);
```

-----------

### getOrdersUpdatedAfter

This method returns the orders updated after the specified date.

| Parameter | Type | Description | Required | Default |
| --------- | :----: | ----------- | :--------: | :-------: |
| `$updateAfter` | DateTimeInterface | Limits the returned order list to those updated after or on a specified date. | Yes | - |
| `$limit` | int | The maximum number of orders that could be returned. | No | 1000 |
| `$offset` | int | Number of orders to skip at the beginning of the list. | No | 0 |
| `$sortBy` | string | Allows choosing the sorting column. The possible values are created_at and updated_at. | No | created_at |
| `$sortDirection` | string | Specify the sort type. The possible are values (ASC, DESC). | No | ASC |

#### Example

```php
$after = new DateTime('-1 week');

// Get orders updated in the past week
$orders = $sdk->globalOrders()->getOrdersUpdatedAfter($after);
```

--------------

### getOrdersUpdatedBefore

This method returns the orders updated before the specified date.

| Parameter | Type | Description | Required | Default |
| --------- | :----: | ----------- | :--------: | :-------: |
| `$updateBefore` | DateTimeInterface | Limits the returned order list to those updated before or on a specified date. | Yes | - |
| `$limit` | int | The maximum number of orders that could be returned. | No | 1000 |
| `$offset` | int | Number of orders to skip at the beginning of the list. | No | 0 |
| `$sortBy` | string | Allows choosing the sorting column. The possible values are created_at and updated_at. | No | created_at |
| `$sortDirection` | string | Specify the sort type. The possible are values (ASC, DESC). | No | ASC |

#### Example
```php
$after = new DateTime('-1 week');

// Get orders updated before the last week
$orders = $sdk->globalOrders()->getOrdersUpdatedAfter($after);
```

---------------

### getOrdersWithStatus

This method returns the orders with the specified status.

| Parameter | Type | Description | Required | Default |
| --------- | :----: | ----------- | :--------: | :-------: |
| `$status` | string | Filters the orders through their status. The possible values are pending, canceled, ready_to_ship, delivered, returned, shipped and failed. | Yes | - |
| `$limit` | int | The maximum number of orders that could be returned. | No | 1000 |
| `$offset` | int | Number of orders to skip at the beginning of the list. | No | 0 |
| `$sortBy` | string | Allows choosing the sorting column. The possible values are created_at and updated_at. | No | created_at |
| `$sortDirection` | string | Specify the sort type. The possible are values (ASC, DESC). | No | ASC |

#### Example

```php
$status = 'pending';
$limit = 10;

// Get the ten oldest orders that are still pending
$orders = $sdk->globalOrders()->getOrdersWithStatus($status, $limit);
```

## Getting order items

### getOrderItems

It's possible to get the items that belong to an Order as follows.

| Parameter | Type | Description | Required | Default |
| --------- | :----: | ----------- | :--------: | :-------: |
| `$orderId` | int | The ID of the order from which you want to retrieve its items. | Yes | - |

#### Example

```php
// Get items which order id is 1234
$orderItems = $sdk->globalOrders()->getOrderItems(1234);
```

-----

### getMultipleOrderItems

| Parameter | Type | Description | Required | Default |
| --------- | :----: | ----------- | :--------: | :-------: |
| `$orderIdList` | array | An array of integers representing multiple orders ids. | Yes | - |

#### Example

```php
$orderIdList = [1234, 5678, 8901];

// Get items from orders #1234, #5678 and #8901
$orderItems = $sdk->globalOrders()->getMultipleOrderItems($orderIdList);
```

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
### setStatusToCanceled

This method cancels a single item.

| Parameter | Type | Description | Required | Default |
| --------- | :----: | ----------- | :--------: | :-------: |
| `$orderItem` | int | The ID of the order item that you want to cancel | Yes | - |
| `$reason` | string | The reason for canceling. Seller Center has several accepted reasons. | Yes | - |
| `$reasonDetail` | string | A reason detail for canceling | No | - |
    
_Note: Valid Reason and ReasonDetail are provided by Seller Center API using [GetFailureReasons](https://sellerapi.sellercenter.net/docs/getfailurereasons).
Even if the ReasonDetail could be retrieved with the mentioned request, the seller could provide any custom detail._

#### Example

```php
// Get items collection from order #1234
$orderItems = $sdk->globalOrders()->getOrderItems(1234);
$reason = 'Valid Reason';
$reasonDetail = 'Valid Reason Detail';

// Indicates to SC that all the order items that belong to the order #1234 should be canceled.

foreach ($orderItems as $orderItem) {
    $sdk->globalOrders()->setStatusToCanceled($orderItem->getOrderItemId(), $reason, $reasonDetail);
}
```

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

## Setting order invoice document

### setInvoiceDocument

This method sets the invoice document.

| Parameter | Type | Description | Required | Default |
| --------- | :----: | ----------- | :--------: | :-------: |
| `$orderItemId` | int | Identifier of the order item | Yes | - |
| `$invoiceNumber` | string | The invoice number | Yes | - |
| `$invoiceDocument` | string | Document XML format | Yes | - |

NOTE: Invoice will be changed for all order items in the package.

#### Example

```php
// Get items collection from order #1234
$orderItems = $sdk->globalOrders()->getOrderItemId(1234);

$firstOrderItem = reset($orderItems);
$invoiceNumber = '123132465465465465456';
$invoiceDocument = '<?xml version="1.0" encoding="UTF-8"?><Node><Item>[Invoice data]]</Item></Node>';

$orderItems = $sdk->globalOrders()->setInvoiceDocument($firstOrderItem, $invoiceNumber, $invoiceDocument);
```
--------------

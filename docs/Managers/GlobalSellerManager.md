# Global Seller Manager

Manager to support Falabella Seller endpoints

## Index
- [Global Seller Manager](#global-seller-manager)
  - [Index](#index)
  - [Getting Seller Statistics](#getting-seller-statistics)
    - [getStatistics](#getstatistics)
      - [Example](#example)
  - [Getting Seller Data](#getting-seller-data)
    - [getSellerByUser](#getsellerbyuser)
      - [Example](#example-1)

-----------

## Getting Seller Statistics

### getStatistics

Returns the seller statistics, i.e. amount of products, and orders.

| Parameter | Type | Description | Required | Default |
| --------- | ---- | ----------- | -------- | ------- |
| `$debug` | bool |  Whether it logs or not the request and response log | No | true | 

#### Example

```php
    $statistics = $sdk->globalSeller()->getStatistics();

    $productsTotal = $statistics->getProductStatistic('Total');
    $productsActive = $statistics->getProductStatistic('Active');
    $productsAll = $statistics->getProductStatistic('All');
    $productsDeleted = $statistics->getProductStatistic('Deleted');
    $productsImageMissing = $statistics->getProductStatistic('ImageMissing');
    $productsInactive = $statistics->getProductStatistic('Inactive');
    $productsLive = $statistics->getProductStatistic('Live');
    $productsPending = $statistics->getProductStatistic('Pending');
    $productsPoorQuality = $statistics->getProductStatistic('PoorQuality');
    $productsSoldOut = $statistics->getProductStatistic('SoldOut');
    
    $ordersTotal = $statistics->getOrderStatistic('Total');
    $ordersCanceled = $statistics->getOrderStatistic('Canceled');
    $ordersDelivered = $statistics->getOrderStatistic('Delivered');
    $ordersDigital = $statistics->getOrderStatistic('Digital');
    $ordersEconomy = $statistics->getOrderStatistic('Economy');
    $ordersExpress = $statistics->getOrderStatistic('Express');
    $ordersFailed = $statistics->getOrderStatistic('Failed');
    $ordersNoExtInvoiceKey = $statistics->getOrderStatistic('NoExtInvoiceKey');
    $ordersNotPrintedPending = $statistics->getOrderStatistic('NotPrintedPending');
    $ordersNotPrintedReadyToShip = $statistics->getOrderStatistic('NotPrintedReadyToShip');
    $ordersPending = $statistics->getOrderStatistic('Pending');
    $ordersReadyToShip = $statistics->getOrderStatistic('ReadyToShip');
    $ordersReturnRejected = $statistics->getOrderStatistic('ReturnRejected');
    $ordersReturnShippedByCustomer = $statistics->getOrderStatistic('ReturnShippedByCustomer');
    $ordersReturnWaitingForApproval = $statistics->getOrderStatistic('ReturnWaitingForApproval');
    $ordersReturned = $statistics->getOrderStatistic('Returned');
    $ordersShipped = $statistics->getOrderStatistic('Shipped');
    $ordersStandard = $statistics->getOrderStatistic('Standard');    
```

-----------

## Getting Seller Data

### getSellerByUser

Returns seller information. i.e. ShortCode, Email and Api Key

#### Example

```php
$seller = $sdk->globalSeller()->getSellerByUser();

```

-----------
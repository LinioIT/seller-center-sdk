# Seller

## Getting the statistics

You can get the seller statistics available in the following example.

```php
    $statistics = $sdk->seller()->getStatistics();

    $productsTotal = $statistics['Products']['Total'];
    $productsActive = $statistics['Products']['Active'];
    $productsAll = $statistics['Products']['All'];
    $productsDeleted = $statistics['Products']['Deleted'];
    $productsImageMissing = $statistics['Products']['ImageMissing'];
    $productsInactive = $statistics['Products']['Inactive'];
    $productsLive = $statistics['Products']['Live'];
    $productsPending = $statistics['Products']['Pending'];
    $productsPoorQuality = $statistics['Products']['PoorQuality'];
    $productsSoldOut = $statistics['Products']['SoldOut'];
    $ordersCanceled = $statistics['Orders']['Canceled'];
    $ordersDelivered = $statistics['Orders']['Delivered'];
    $ordersDigital = $statistics['Orders']['Digital'];
    $ordersEconomy = $statistics['Orders']['Economy'];
    $ordersExpress = $statistics['Orders']['Express'];
    $ordersFailed = $statistics['Orders']['Failed'];
    $ordersNoExtInvoiceKey = $statistics['Orders']['NoExtInvoiceKey'];
    $ordersNotPrintedPending = $statistics['Orders']['NotPrintedPending'];
    $ordersNotPrintedReadyToShip = $statistics['Orders']['NotPrintedReadyToShip'];
    $ordersPending = $statistics['Orders']['Pending'];
    $ordersReadyToShip = $statistics['Orders']['ReadyToShip'];
    $ordersReturnRejected = $statistics['Orders']['ReturnRejected'];
    $ordersReturnShippedByCustomer = $statistics['Orders']['ReturnShippedByCustomer'];
    $ordersReturnWaitingForApproval = $statistics['Orders']['ReturnWaitingForApproval'];
    $ordersReturned = $statistics['Orders']['Returned'];
    $ordersShipped = $statistics['Orders']['Shipped'];
    $ordersStandard = $statistics['Orders']['Standard'];
    $ordersTotal = $statistics['Orders']['Total'];
```

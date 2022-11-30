# Seller

## Getting the statistics

You can get the seller statistics available in the following example.

```php
    $statistics = $sdk->seller()->getStatistics();

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

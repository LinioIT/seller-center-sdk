# Document

## Getting the documents

In order to get a document from seller center it's required to specify a type, which can be 'invoice', 'exportInvoice', 'shippingLabel', 'shippingParcel', 'carrierManifest', or 'serialNumber' and the order item IDs. 

The following method returns a collection of invoices:

```php
$documentType = 'invoice';
$orderItemIds = [1234, 4567, 890];

// Get invoice of order item #1234, #4567 and #890
$document = $sdk->documents()->getDocument($documentType, $orderItemIds);
```

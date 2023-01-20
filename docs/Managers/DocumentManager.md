# Document Manager

Manager to support Linio/Falabella Document endpoints

## Index

- [Document Manager](#document-manager)
  - [Index](#index)
  - [Getting the documents](#getting-the-documents)
    - [getDocument](#getdocument)
      - [Example](#example)

-----------

## Getting the documents

### getDocument

In order to get a document from seller center it's required to specify a type, which can be 'invoice', 'exportInvoice', 'shippingLabel', 'shippingParcel', 'carrierManifest', or 'serialNumber' and the order item IDs.

| Parameter | Type | Description | Required | Default |
| --------- | :----: | ----------- | :--------: | :-------: |
| `$documentType` | string | One of 'invoice', 'exportInvoice', 'shippingLabel', 'shippingParcel', 'carrierManifest', or "serialNumber". | Yes | - |
| `$orderItemIds` | int[] | Array of OrderItemsIds | Yes | - |
| `$debug` | bool |  Whether it logs or not the request and response log | No | true | 

#### Example

```php
$documentType = 'invoice';
$orderItemIds = [1234, 4567, 890];

// Get invoice of order item #1234, #4567 and #890
$document = $sdk->documents()->getDocument($documentType, $orderItemIds);
```
-----------
# Quality Control Manager

Manager to support Linio/Falabella Brands endpoints

## Index

- [Quality Control Manager](#quality-control-manager)
  - [Index](#index)
  - [Getting product status from Quality Control](#getting-product-status-from-quality-control)
    - [getAllQcStatus](#getallqcstatus)
      - [Example](#example)
    - [getQcStatusBySkuSellerList](#getqcstatusbyskusellerlist)
      - [Example](#example-1)

## Getting product status from Quality Control

### getAllQcStatus

It's possible to retrieve the approval status of a product using the method `getAllQcStatus`

| Parameter | Type | Description | Required | Default |
| --------- | :----: | ----------- | :--------: | :-------: |
| `$limit` | int |  The maximum number of Qc Statuses that could be returned | No | 100 |
| `$offset` | int |  Number of Qc Statuses to skip. | No | 0 | 


#### Example

```php
// Get ten quality control status skipping onex1
$qcStatuses = $sdk->qualityControl()->getAllQcStatus(10, 1);
```
----------------

### getQcStatusBySkuSellerList

If it's necessary, there is the possibility of specifying the seller's SKU whose quality control status is needed. 

| Parameter | Type | Description | Required | Default |
| --------- | :----: | ----------- | :--------: | :-------: |
| `$skuSellerList` | string[] |  A list of strings representing product's SKU. | Yes | - |
| `$limit` | int |  The maximum number of Qc Statuses that could be returned | No | 100 |
| `$offset` | int |  Number of Qc Statuses to skip. | No | 0 |
| `$debug` | bool |  Whether it logs or not the request and response log | No | true | 

#### Example

```php
$sellerSkus = ['Sku1', 'Sku2', 'Sku3'];

// Get quality control status of specified products
$qcStatuses = $sdk->qualityControl()->getQcStatusBySkuSellerList($sellerSkus);
```

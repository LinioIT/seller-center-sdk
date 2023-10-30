# Brand Manager

Manager to support Linio/Falabella Brands endpoints

## Index

- [Brand Manager](#brand-manager)
  - [Index](#index)
  - [Getting the brands](#getting-the-brands)
    - [Example](#example)

-----------

## Getting the brands

The products need an existent brand in the Seller Center platform so firstly it's necessary to get the valid brand list.

| Parameter | Type | Description | Required | Default |
| --------- | ---- | ----------- | -------- | ------- |
| `$debug` | bool |  Whether it logs or not the request and response log | No | true | 

### Example

```php
    $brands = $sdk->brands()->getBrands();

foreach($brands as $brand) {
    $brandName = $brand->getName();
    $brandId = $brand->getBrandId();
    $brandGlobalIdentifier = $brand->getGlobalIdentifier();
    echo sprintf('Brand: %s (ID: %s | Global Identifier: %s)\r\n', $brandName, $brandId, $brandGlobalIdentifier);
}
```

-----------

# Brand

## Getting the brands

The products need an existent brand in the Seller Center platform so firstly it's necessary to get the valid brand list.

```php
    $brands = $sdk->brands()->getBrands();

foreach($brands as $brand) {
    $brandName = $brand->getName();
    $brandId = $brand->getBrandId();
    $brandGlobalIdentifier = $brand->getGlobalIdentifier();
    echo sprintf('Brand: %s (ID: %s | Global Identifier: %s)\r\n', $brandName, $brandId, $brandGlobalIdentifier);
}
```

# Quality control

## Getting product status from Quality Control

It's possible to retrieve the approval status of a product using the method `getAllQcStatus` with the following parameters:

- `$limit`: The maximum number of Qc Statuses that could be returned. The default value is 100. `Optional`
- `$offset`: Number of Qc Statuses to skip. The default is 0. `Optional`

**Note: If none of those parameters is passed, the entire QC status list will be returned.**
    
```php
// Get ten quality control status skipping onex1
$qcStatuses = $sdk->qualityControl()->getAllQcStatus(10, 1);
```
----------------

If it's necessary, there is the possibility of specifying the seller's SKU whose quality control status is needed. 
The `getQcStatusBySkuSellerList` method adds one extra parameter which is the SKU array:

- `$skuSellerList`: A list of strings representing product's SKU. `Required`
- `$limit`: The maximum number of Qc Statuses that could be returned. The default value is 100. `Optional`
- `$offset`: Number of Qc Statuses to skip. The default is 0. `Optional`

```php
$sellerSkus = ['Sku1', 'Sku2', 'Sku3'];

// Get quality control status of specified products
$qcStatuses = $sdk->qualityControl()->getQcStatusBySkuSellerList($sellerSkus);
```

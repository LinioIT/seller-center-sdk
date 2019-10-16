# Products

## Getting products

There are multiple ways of getting a collection of Product.
       
### getProductsFromParameters 

Provides you the possibility to use the most common parameters in one call.

| Parameter | Type | Description | Required | Default |
| --------- | :----: | ----------- | :--------: | :-------: |
| $createdAfter | DateTimeInterface |  Filters the products using the specified date. | No | - | 
| $createdBefore| | Filters the products using the specified date | No | - |
| $search| string | Filters the products that contain the string in their name and/or SKU | No | - | 
| $filter | string | Specify filter type, Possible values (all, live, inactive, deleted, image-missing, pending, rejected, sold-out) | No| all |
| $limit | integer |  The maximum number of products that could be returned | No | 1000 | 
| $offset | integer | Number of products to skip at the beginning of the list. | No | 0 | 
| $skuSellerList | array | Array of strings representing multiple sellers SKUs | No | - | 
| $updatedAfter |  DateTimeInterface | Filters the products using the specified date. The date provided will be included in the filter. | No | - | 
| $updatedBefore | DateTimeInterface | Filters the products using the specified date. The date provided will be included in the filter. | No | - | 
   

Example:   
```php
// Get all products using default parameters
$products = $sdk->products()->getProductsFromParameters();
```

-----------


## getAllProducts 

Returns all products.

| Parameter | Type | Description | Required | Default |
| --------- | :----: | ----------- | :--------: | :-------: |
| $limit | integer |  The maximum number of products that could be returned | No | 1000 | 
| $offset | integer | Number of products to skip at the beginning of the list. | No | 0 | 


Example:
```php
//Get all products
$products = $sdk->products()->getAllProducts(ProductManager::DEFAULT_LIMIT, ProductManager::DEFAULT_OFFSET);
```

-----------


### getProductsCreatedAfter 

Returns the products created after the specified date.

| Parameter | Type | Description | Required | Default |
| --------- | :----: | ----------- | :--------: | :-------: |
| $createdAfter | DateTimeInterface |  Filters the products using the specified date. | Yes | - | 
| $limit | integer |  The maximum number of products that could be returned | No | 1000 | 
| $offset | integer | Number of products to skip at the beginning of the list. | No | 0 | 


Example:    
```php
$after = new DateTime('-1 month');

// Get products created in the past month
$products = $sdk->products()->getProductsCreatedAfter($after, ProductManager::DEFAULT_LIMIT, ProductManager::DEFAULT_OFFSET);
```

-----------


### getProductsCreatedBefore

Returns products created before the specified date.

| Parameter | Type | Description | Required | Default |
| --------- | :----: | ----------- | :--------: | :-------: |
| $createdBefore| | Filters the products using the specified date | Yes | - |
| $limit | integer |  The maximum number of products that could be returned | No | 1000 | 
| $offset | integer | Number of products to skip at the beginning of the list. | No | 0 | 

 
Example:   
```php
$before = new DateTime('2018/01/01');

// Get products created before the first of January of 2018.
$products = $sdk->products()->getProductsCreatedBefore($before);
```

-----------


### getProductsUpdatedAfter

Returns products updated after the specified date.

| Parameter | Type | Description | Required | Default |
| --------- | :----: | ----------- | :--------: | :-------: |
| $updatedAfter |  DateTimeInterface | Filters the products using the specified date. The date provided will be included in the filter. | Yes | - |
| $limit | integer |  The maximum number of products that could be returned | No | 1000 | 
| $offset | integer | Number of products to skip at the beginning of the list. | No | 0 | 
     
 
Example:   
```php
$after = new DateTime('-1 week');

// Get products updated in the past week
$products = $sdk->products()->getProductsUpdatedAfter($after);
```

-----------


### getProductsUpdatedBefore 

Returns products updated before the specified date.
    
| Parameter | Type | Description | Required | Default |
| --------- | :----: | ----------- | :--------: | :-------: |
| $updatedBefore | DateTimeInterface | Filters the products using the specified date. The date provided will be included in the filter. | No | - | 
| $limit | integer |  The maximum number of products that could be returned | No | 1000 | 
| $offset | integer | Number of products to skip at the beginning of the list. | No | 0 | 


Example:    
```php
$before = new DateTime('2018/01/01');

// Get products updated before 2018.
$products = $sdk->products()->getProductsUpdatedBefore($before);
```

-----------


###  Search products

It's possible to retrieve a specific group of products that contain a specific string in their name or SKU using the method `searchProduct`. 

| Parameter | Type | Description | Required | Default |
| --------- | :----: | ----------- | :--------: | :-------: |
| $search | String| Filter the products that the search string is contained in the product's name and/or SKU. | Yes | - | 
| $limit | integer |  The maximum number of products that could be returned | No | 1000 | 
| $offset | integer | Number of products to skip at the beginning of the list. | No | 0 | 

Note: If the search value is NULL, it returns all the products.


Example:    
```php
$value = "Name - SKU - 123";

// Retrieve the products that contain the value in their name and/or SKU.
$products = $sdk->products()->searchProducts($value);
```

-----------


### Filter products 

It's possible to retrieve a specific group of products that comply with a filter of its status using the method `filterProduct`. 

| Parameter | Type | Description | Required | Default |
| --------- | :----: | ----------- | :--------: | :-------: |
| $filter | String| Specify filter type, Possible values (all, live, inactive, deleted, image-missing, pending, rejected, sold-out) | Yes | all | 
| $limit | integer |  The maximum number of products that could be returned | No | 1000 | 
| $offset | integer | Number of products to skip at the beginning of the list. | No | 0 | 


Note: If the search value is invalid, return the products with the default filter (all).
   

Example:    
```php
$filter = "rejected";

// Retrieve the products that comply with the filter "rejected".
$products = $sdk->products()->filterProducts($filter);
```

-----------

### getProductsBySellerSku 

Returns those products that contain an SKU in skuSellerList.

| Parameter | Type | Description | Required | Default |
| --------- | :----: | ----------- | :--------: | :-------: |
| $skuSellerList | String| Array of strings representing multiple sellers SKUs. | Yes | - | 
| $limit | integer |  The maximum number of products that could be returned | No | 1000 | 
| $offset | integer | Number of products to skip at the beginning of the list. | No | 0 | 

Note: If the skuSellerList is empty, throw the EmptyArgumentException with the message "The parameter skuSellerList should not be null.


Example:    
```php
$skuSellerList = ["Sku-123", "Sku - 456", "Sku - 789"];

// Get products with SKU "Sku-123", "Sku - 456", "Sku - 789".
$products = $sdk->products()->getProductsBySellerSku($skuSellerList);
```

-----------


## Creating a Product
       
The method `productCreate` could be used to create one or multiple products.

| Parameter | Type | Description | Required | Default |
| --------- | :----: | ----------- | :--------: | :-------: |
| $products | array | Array of Product entities. | Yes | - | 

Note: The creation of the product is asynchronous. The process will return a  feed ID to look for the creation status. The images cannot be created with this method. 


Example:
```php
$products = [ $product_1, $product_2, $product_3];

// Create the products.
$products = $sdk->products()->productCreate($products);
```

-----------


## Updating a Product

It's possible to update one or many products at once using the method `productUpdate` will be used to update a product or multiple products.

| Parameter | Type | Description | Required | Default |
| --------- | :----: | ----------- | :--------: | :-------: |
| $products | array | Array of Product entities. | Yes | - | 

Note: It's not possible to update the images with this method. 


Example:
```php
$products = [ $product_1, $product_2, $product_3];

// Update the products.
$products = $sdk->products()->productUpdate($products);
```

-----------


## Removing a Product

It's possible to remove one or many products at once using the method `productRemove`.

| Parameter | Type | Description | Required | Default |
| --------- | :----: | ----------- | :--------: | :-------: |
| $products | array | Array of Product entities. | Yes | - | 

Note: The removal of the product is asynchronous. The process will return a feed ID to check the removal status.


Example:
```php
$products = [ $product_1, $product_2, $product_3];

// Update the products.
$products = $sdk->products()->productRemove($products);
```

-----------


### Adding images
       
Once the product is created, it's possible to add images whit the method `addImage`.

| Parameter | Type | Description | Required | Default |
| --------- | :----: | ----------- | :--------: | :-------: |
| $products | array | Array of Product entities. | Yes | - | 

Example:
```php
$products = [ $product_1, $product_2, $product_3];

// Add the images to the specified products.
$products = $sdk->products()->addImage($products);
```

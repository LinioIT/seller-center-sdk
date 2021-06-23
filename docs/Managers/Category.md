# Category

## Getting the category tree

### getCategoryTree

Seller Center has its category tree and that's mean that products only work using categories that belong to it. It's possible to get valid categories using this method.

#### Example

```php
$categories = $sdk->categories()->getCategoryTree();
```
## Getting the attributes

### getCategoryAttributes

Products may have attributes depending on their primary category, and some of them could be mandatory.

To get the attributes for a specific category it's possible to use the method getCategoryAttributes.

| Parameter | Type | Description | Required | Default |
| --------- | ---- | ----------- | -------- | ------- |
| `$categoryId` | int | The ID of the category of which you want to get the attributes from | Yes | - |

### Example

```php
$categories = $sdk->categories()->getCategoryTree();

// Get attributes of the first category
$categoryAttributes = $sdk->categories()->getCategoryAttributes($categories[0]->getId());
```
## Getting the categories which use a specific attribute

### getCategoriesByAttributesSet

Using this method it is possible to find a category that uses a specific attribute set.

| Parameter | Type | Description | Required | Default |
| --------- | ---- | ----------- | -------- | ------- |
| `$attributesSetIds` | int[] | The list of attributes IDs to get the categories | Yes | - |

#### Example

```php
$attributesIds = [0, 1];

// Search for categories that use the attribute id 0 and 1
$attributesSet = $sdk->categories()->getCategoriesByAttributesSet($attributesIds);
```

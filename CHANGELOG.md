Changelog
===================


Unreleased
----------------------



Changelog v0.2.0 (2019-09-02)
----------------------

### Added 

* Request and response logs in the managers
* Shipment manager into the sdk to get the shipment providers from seller center 
* jsonSerialize into models
* `Product::fromSku` to use when only the images will be updated
* Product remove feature into the product manager
* `getFailureReasons` into the Orders manager
* Supports multiple status for a single order
* `Images::addManyFromString` to add multiple images using their url

### Fixed
* Feed manager. Now its checks the right object
* `extraAttributes` in orders. Now `extraAttributes` its processed as a `string`
* Uninitialized collection for orders
* The category behavior when it contains commas 

### Changed

* Manager's return array. Now its return a clean array by use array_values
* The product constructor. `new Product` was replaced by `Product::fromBasicData`
* The `addImage` parameters. Now a simple array it's supported
* `Brand::fromProduct` method. Now its called `Brand::fromName`
* `Brand::fromBrand` method. Now its called `Brand::build`
* `Orders::fromOrders` method. Not its called `Orders::fromData`
* `Orders::fromOrderItems` method. Not its called `Orders::fromItems`
* `Orders::getStatus` method. Not its called `Orders::getStatuses`
* `Orders::getStatuses`. Instead to return an string, now return an array with all the statuses


Changelog [v0.1.0](https://github.com/LinioIT/seller-center-sdk/releases/tag/v0.1.0) (2019-04-24)
----------------------

Initial Release


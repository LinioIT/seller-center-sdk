# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added
* Support linio util v3
* Support to update seller sku

### Changed
* Update Readme documentation
* Make `ProductData` constructor's parameters optional
* Do not build XML request with `null` values (useful when not updating the entire product)

### Fixed

* Support feed's errors with empty SellerSku
* Fix CategoryAttribute returning `0` (zero) instead of `null`

## [0.2.1] - 2019-11-04

### Added

* Support array value in `ProductData`

### Fixed

* Update feed documentation
* Fix error while processing numerical SKU by `ProductManager::addImage`

## [0.2.0] - 2019-09-02

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

## 0.1.0 - 2019-04-24

Initial Release

[Unreleased]: https://github.com/LinioIT/seller-center-sdk/compare/v0.2.1...HEAD
[0.2.1]: https://github.com/LinioIT/seller-center-sdk/releases/tag/v0.2.1
[0.2.0]: https://github.com/LinioIT/seller-center-sdk/releases/tag/v0.2.0

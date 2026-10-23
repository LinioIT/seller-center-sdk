# Seller Center PHP SDK

https://img.shields.io/badge/License-BSD%203--Clause-blue.svg](https://opensource.org/licenses/BSD-3-Clause)

## Installation

The recommended way to install the SDK is through [Composer](https://getcomposer.org).

> ### PHP Version Support
>
> This repository currently maintains two active branches:
>
> - **`main`**: Primary development branch with support for **PHP 8.3+**. All new features, improvements, and future enhancements will be delivered here.
> - **`gsc-master`**: Maintenance branch for **PHP 7.x** users. This branch will continue receiving bug fixes, compatibility updates, and security-related changes until **December 2026**.
>
> If your application is running on PHP 7.x, continue using the `gsc-master` branch. New integrations and projects running on PHP 8 should use the `main` branch.

### Composer Installation

Add the repository to your `composer.json`:

```json
{
    "repositories": [
        {
            "type": "git",
            "url": "https://github.com/LinioIT/seller-center-sdk.git"
        }
    ]
}
```

Install the version that matches your PHP runtime:

**PHP 8.3+ (recommended)**

```bash
composer require "linio/seller-center-sdk:dev-main"
```

**PHP 7.x (supported until December 2026)**

```bash
composer require "linio/seller-center-sdk:dev-gsc-master"
```

## Quick Start

### Configuration

To interact with the Seller Center platform, you need a User ID, an API Key, and the API URL and version of the Seller Center environment you want to consume.

These values are provided through a `Configuration` object:

```php
$configuration = new \Linio\SellerCenter\Application\Configuration(
    'api-key-provided',
    'api-username-provided',
    'https://environment-seller-center-api.com',
    '1.0'
);
```

### Accessing the Functionalities

All interaction with the platform is performed through the `SellerCenterSdk` class. To create an instance, you need to provide a configuration object and optionally an HTTP client.

> **Note:** The SDK supports Guzzle v6+ and any PSR-7 compatible HTTP client.
>
> - If you want to use **Guzzle v5**, you must install `php-http/guzzle5-adapter` and **must not** pass a client into the constructor.
> - If you use **Guzzle v6**:
>   - Passing a client to the constructor requires no additional dependencies.
>   - Not passing a client requires `php-http/guzzle6-adapter`.

#### Client Use Case

```php
$client = new Client(); // Guzzle >= 6 or any PSR-7 HTTP Client

$configuration = new \Linio\SellerCenter\Application\Configuration(
    'api-key-provided',
    'api-username-provided',
    'https://environment-seller-center-api.com',
    '1.0'
);

$sdk = new \Linio\SellerCenter\SellerCenterSdk($configuration, $client);
```

#### Integrator Use Case

If you are an integrator, additional metadata is required to build the mandatory `User-Agent` header:

```php
$configuration = new \Linio\SellerCenter\Application\Configuration(
    'api-key-provided',
    'api-username-provided',
    'https://environment-seller-center-api.com',
    '1.0',
    'SDK',                 // Source
    'seller-id',
    'PHP',                 // Language
    PHP_VERSION,           // Language version
    'integrator-name',
    'cl'                   // Country
);

$sdk = new \Linio\SellerCenter\SellerCenterSdk($configuration, $client);
```

#### Without Client (or Guzzle v5)

> **Note**
>
> - Guzzle v5:
>
>   ```bash
>   composer require php-http/guzzle5-adapter
>   ```
>
> - Guzzle v6:
>
>   ```bash
>   composer require php-http/guzzle6-adapter
>   ```
>
> - PSR-7 compatible clients require no additional adapter dependencies.

```php
$configuration = new \Linio\SellerCenter\Application\Configuration(
    'api-key-provided',
    'api-username-provided',
    'https://environment-seller-center-api.com',
    '1.0'
);

$sdk = new \Linio\SellerCenter\SellerCenterSdk($configuration);
```

Once you have an SDK instance, you can access all functionalities through the available managers:

```php
$result = $sdk->manager()->getSome();
```

For example, to retrieve all available brands:

```php
$brandList = $sdk->brands()->getBrands();
```

## Available Managers

- docs/Managers/SellerManager.md
- docs/Managers/GlobalSellerManager.md
- docs/Managers/OrderManager.md
- docs/Managers/GlobalOrderManager.md
- docs/Managers/ProductManager.md
- docs/Managers/GlobalProductManager.md
- docs/Managers/BrandManager.md
- docs/Managers/CategoryManager.md
- docs/Managers/DocumentManager.md
- docs/Managers/FeedManager.md
- docs/Managers/QualityControlManager.md
- docs/Managers/WebhookManager.md
- docs/Managers/ShipmentManager.md

## Logging

The SDK accepts a PSR-compatible logger implementation and uses it to register requests and responses exchanged with Seller Center.

### Basic Logging

```php
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$logger = new Logger('myLogger');
$logger->pushHandler(new StreamHandler(__DIR__ . '/sdk.log'));

$sellerCenterSdk = new SellerCenterSdk(
    $configuration,
    $client,
    $logger
);
```

### Separate Info and Debug Logs

Be careful when enabling debug logging, as log files can grow very quickly.

```php
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$logger = new Logger('myLogger');

$logger->pushHandler(
    new StreamHandler(__DIR__ . '/sdk.log', Logger::INFO)
);

$logger->pushHandler(
    new StreamHandler(__DIR__ . '/sdk-debug.log', Logger::DEBUG)
);

$sellerCenterSdk = new SellerCenterSdk(
    $configuration,
    $client,
    $logger
);
```

## Contributing

Feel free to submit contributions through Pull Requests.

Please ensure the following requirements are met:

- docs/Contribute/Standards.md
- [PSR-2 Coding Standard](https://www.php-fig.org/psr/psr-2/)
- docs/Contribute/Tests.md
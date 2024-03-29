Seller Center PHP SDK
=====================
[![License](https://img.shields.io/badge/License-BSD%203--Clause-blue.svg)](https://opensource.org/licenses/BSD-3-Clause)
[![Codefresh build status]( https://g.codefresh.io/api/badges/pipeline/linioit/seller-center-sdk%2Fdefault?branch=master&key=eyJhbGciOiJIUzI1NiJ9.NWNhNzllZDQzNjVkMGNlMjdjOTYzNzI4.OK7sUA-U_zNwHsu1lm9Xw2DAX9hj0MUjFH1CUUK7xhM&type=cf-1)]( https%3A%2F%2Fg.codefresh.io%2Fpipelines%2Fdefault%2Fbuilds%3FrepoOwner%3DLinioIT%26repoName%3Dseller-center-sdk%26serviceName%3DLinioIT%252Fseller-center-sdk%26filter%3Dtrigger%3Abuild~Build%3Bbranch%3Amaster%3Bpipeline%3A5e600de27efa9000aa57452f~default)


Installation
------------

The recommended way to install the SDK is [through composer](http://getcomposer.org)

We have a new version to support Falabella and Linio platform, please use and follow instructions of README from branch [gsc-master](https://github.com/LinioIT/seller-center-sdk/tree/gsc-master)

Edit your composer.json to add the repository URL:

    {
        "repositories": [
            {
                "type": "git",
                "url": "https://github.com/LinioIT/seller-center-sdk.git"
            }
        ]
    }

Then require the package:

    $ composer require linio/seller-center-sdk

Quick start
-----

### Configuration

To interact with the Seller Center platform you need to ask for a UserID and API KEY and know the URL and version of the service you'll consume.

Those values will be used through a `Configuration` object as follows.

```php
$configuration = new \Linio\SellerCenter\Application\Configuration('api-key-provided', 'api-username-provided', 'https://enviroment-seller-center-api.com', '1.0');
```

### Accessing the functionalities

All the interaction with the platform will be guided through the SDK class **SellerCenterSdk**.  To create it you need to provide a specific configuration and optionally a *HTTP Client*. 
> Note: We support HTTP Client for Guzzle v6 higher or any PSR7 HTTP Client. 
>* If you want to use Guzzle v5 you MUST install as dependency `php-http/guzzle5-adapter` And You MUST NOT pass a client into constructor.
>* For Guzzle v6 take it in consideration.
>   * If you are using Guzzle v6 and passing a client into constructor you don't need any dependency. 
>   * If you are using Guzzle v6 and not passing a client into constructor you will need a `php-http/guzzle6-adapter` dependency. 

#### Client use case.
```php
$client = new Client(); // Guzzle >= v6 only or a PSR7 HTTP Client

$configuration = new \Linio\SellerCenter\Application\Configuration('api-key-provided', 'api-username-provided', 'https://enviroment-seller-center-api.com', '1.0');

$sdk = new \Linio\SellerCenter\SellerCenterSdk($configuration, $client);
```

#### Without Client or Guzzle v5 use case.
>Note:
>* case Guzzle v5
>   * ```$ composer require php-http/guzzle5-adapter```
>* case Guzzle v6
>   * ```$ composer require php-http/guzzle6-adapter```
>* For PSR7 HTTP Client no further more dependency is required.

```php
$configuration = new \Linio\SellerCenter\Application\Configuration('api-key-provided', 'api-username-provided', 'https://enviroment-seller-center-api.com', '1.0');

$sdk = new \Linio\SellerCenter\SellerCenterSdk($configuration);
```
One you have the SDK instance you can access to all the functionalities group in a series of managers.

```php
$result = $sdk->manager()->getSome();
```

If you want to retrieve all the available brands in Seller Center, you just need to use the Brand manager as follows:

```php
$brandList = $sdk->brands()->getBrands();
```

### Knowing the managers

Here is a list of the actual managers in the SDK:

- [BrandManager](docs/Managers/Brand.md)
- [CategoryManager](docs/Managers/Category.md)
- [DocumentManager](docs/Managers/Document.md)
- [FeedManager](docs/Managers/Feed.md)
- [OrderManager](docs/Managers/Order.md)
- [ProductManager](docs/Managers/Product.md)
- [QualityControlManager](docs/Managers/QcStatus.md)
- [WebhookManager](docs/Managers/Webhook.md)
- [Shipment](docs/Managers/Shipment.md)

### Logging

The SDK accept a Logging interface that will use to register every request and response through your application and 
Seller Center in DEBUG mode.

```php
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$logger = new Logger('myLogger');
$logger->pushHandler(new StreamHandler(__DIR__.'/sdk-log.log'));

$sellerCenterSdk = new SellerCenterSdk($configuration, $client, $logger);
```

Be very careful by using the SDK in debug mode, it will increase the size of log files very quickly. If you need to register every response, we recommend adding multiple log handlers. 


```php
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$logger = new Logger('myLogger');
$logger->pushHandler(new StreamHandler(__DIR__.'/sdk-log.log', Logger::INFO));
$logger->pushHandler(new StreamHandler(__DIR__.'/sdk-log-debug.log', Logger::DEBUG));

$sellerCenterSdk = new SellerCenterSdk($configuration, $client, $logger);
```

### Contributing

Feel free to send your contributions as a PR. Make sure to fulfill the followings items.

* [Commit standards](docs/Contribute/Standards.md)
* [PSR-2](https://www.php-fig.org/psr/psr-2/)
* [Tests](docs/Contribute/Tests.md)

Configuring test environment
----------------

All the integration tests need a specific configuration for each environment and those variables should be defined in the phpunit.xml file. 

You can run both, Functional and Unit tests, without defining the environment but not the Integration tests. 

```xml
<env name='APP_*' value='{"endpoint": "https://sellercenter-api.linio.cl", "version": "1.0", "key": "CA0BE82A1662B86AEF4484D5326677391A653939D2CD6759155C5834E3F5D076","username": "my.email@linio.com"}'/>
```

Each service has its own integration test and it possible to define a different environment for each one if it's necessary.

This should be a JSON that includes the following values:
- "endpoint": It corresponds with the environment URL.
- "version": The version used by the API.
- "key": The key value corresponds to the API key generated in the Seller Center Integration panel. 
- "username": The username value is your Seller Center username bound with the API key.

```xml
<env name="SC_EXISTENT_PRODUCT_ID" value="7205109" />
```
A valid product ID in the environment.  

```xml
<env name="SC_EXISTENT_ORDER_ID" value="4639651"/>
```
A valid order ID in the environment.

```xml
<env name="ORDER_LIMIT" value="10"/>
```
A integer for limiting orders quantity from response.

```xml
<env name="ORDER_CREATED_AFTER" value="2018-09-01 00:00:00"/>
```
A string with the format written above for getting orders created after that date.

```xml
<env name="ORDER_CREATED_BEFORE" value="2018-09-13 00:00:00"/>
```
A string with the format written above for getting orders created before that date.

```xml
<env name="ORDER_UPDATED_AFTER" value="2018-09-01 00:00:00"/>
```
A string with the format written above for getting orders updated after that date.

```xml
<env name="ORDER_UPDATED_BEFORE" value="2018-09-13 00:00:00"/>
```
A string with the format written above for getting orders updated before that date.

```xml
<env name="SC_EXISTENT_DOCUMENT_ORDERITEM_ID" value="6575812"/>
``` 
A valid order item ID in the environment for get its document.

## Local setup

**Prerequisites**:
- [docker-compose](https://docs.docker.com/compose/install/)

First, we need to build the local docker image. To do this, run the following command:

> `docker compose build`

We will be using an alias for executing the development commands.

> `alias dcli='docker compose -f docker-compose.cli.yml run --rm'`

**Install dependencies**:

> `dcli composer install`

`symfony/var-dumper` is pinned to `7.4.21` in `require-dev`. The other Symfony
components are locked to their latest available `7.4` releases up to `7.4.21`,
without `conflict` constraints. `composer install` preserves these versions;
`composer update` may change the transitive versions. Symfony Contracts and
Polyfills use independent version numbers.

Running tests
-------------

To run the project tests and validate the coding standards:

    $ dcli composer test

To run the unit, helper and functional tests (including V2) you can use:

    $ dcli phpunit

The default configuration excludes integration tests, which require environment
credentials. To run them after configuring the environment, use:

    $ dcli phpunit tests/Integration

To run specific unit test you can use --filter option:

    $ dcli phpunit --filter=ClassName::MethodName

PHPUnit data providers must be public static methods. Return input data from
providers and build fixtures that depend on the test instance inside the test
method, after `setUp()` has run.

### JSON compatibility

JSON encoding and decoding use `Linio\SellerCenter\Helper\Json`, backed by PHP's
native JSON extension, without `linio/util`. The helper preserves associative
arrays, default escaping, `LogicException` error messages and the previous
empty-input behavior (including decoding `''` and `'0'` as `null`). Its regression
tests are in `tests/Helper/JsonTest.php`.

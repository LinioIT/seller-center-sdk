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


Running tests
-------------

To run the project tests and validate the coding standards:

    $ composer test

To run specific unit tests you can use --filter option:

    $ php vendor/bin/phpunit --filter=ClassName::MethodName

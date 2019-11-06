# Feed

A feed is a piece of data that indicates the status of a specific process. 

## Getting the feed

It's possible to get a specific feed by providing the identifier into the  ```getFeedStatusById``` method.

```php
$feedId = '6b4e9a86-2a65-44e3-ae8e-3f752fc265f8';

// Get that specific feed
$feed = $sdk->feeds()->getFeedStatusById($feedId);
```

Also it's possible to get an entire feed list as an array:

```php
$feedList = $sdk->feeds()->getFeedList();
```

The `Feed` instance has a getter for every attribute in the XML response.

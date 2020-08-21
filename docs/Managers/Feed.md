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

Or partial feed list with the following parameters:

| Parameter       | Type               | Description                                                                     | Required | Default |
|-----------------|--------------------|---------------------------------------------------------------------------------|----------|---------|
| `$offset`       | ?int               | Zero-based offset into the list of all feeds.                                   | No       | -       |
| `$pageSize`     | ?int               | The number of entries to retrieve, i.e. the page size.                          | No       | -       |
| `$status`       | ?string            | If supplied, only feeds with this status are returned.                          | No       | -       |
| `$createdAfter` | ?DateTimeInterface | If supplied, only feeds created after this date will be included in the result. | No       | -       |
| `$updatedAfter` | ?DateTimeInterface | If supplied, only feeds updated after this date will be included in the result. | No       | -       |

**Example:**

```php
$offset = 1;
$limit = 1;

$feedList = $sdk->feeds()->getFeedOffsetList($offset, $limit);
```

The `Feed` instance has a getter for every attribute in the XML response.

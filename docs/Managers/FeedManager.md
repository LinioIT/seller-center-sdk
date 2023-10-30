# Feed Manager

Manager to support Linio/Falabella Feed endpoints.

A feed is a piece of data that indicates the status of a specific process.

## Index

- [Feed Manager](#feed-manager)
  - [Index](#index)
  - [Getting the feed](#getting-the-feed)
    - [getFeedStatusById](#getfeedstatusbyid)
      - [Example](#example)
      - [Example](#example-1)
  - [Canceling feed](#canceling-feed)
    - [feedCancel](#feedcancel)
      - [Example](#example-2)

-----------

## Getting the feed

### getFeedStatusById

It's possible to get a specific feed by providing the identifier into the  ```getFeedStatusById``` method.

| Parameter | Type | Description | Required | Default |
| --------- | ---- | ----------- | -------- | ------- |
| `$feedId` | string |  The ID of the feed to retrieve | Yes | - |
| `$debug` | bool |  Whether it logs or not the request and response log | No | true | 

#### Example

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
| `$debug` | bool |  Whether it logs or not the request and response log | No | true | 

#### Example

```php
$offset = 1;
$limit = 1;

$feedList = $sdk->feeds()->getFeedOffsetList($offset, $limit);
```

The `Feed` instance has a getter for every attribute in the XML response.

-----------

## Canceling feed

### feedCancel

It's possible to cancel a feed using the method `feedCancel`.

| Parameter | Type | Description | Required | Default |
| --------- | ---- | ----------- | -------- | ------- |
| `$feedId` | string | The ID of the feed to cancel | Yes | - |
| `$debug` | bool |  Whether it logs or not the request and response log | No | true | 

#### Example

```php
$feedId = '6b4e9a86-2a65-44e3-ae8e-3f752fc265f8';

$feedResponse = $sdk->feeds()->feedCancel($feedId);
```

-----------

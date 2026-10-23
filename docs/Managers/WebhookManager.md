# Webhook Manager

Manager to support Linio/Falabella Webhook endpoints

Many operations of Seller Center will be performed in an asynchrony way. The operation itself will return a feed id
and it's possible to track them using the feed manager furthermore, Seller Center will inform the changes in the
operations to the URL registered if you have it. This should be the way to handle these events.

To get the alerts, you need to register an url and process the payload individually. To know more about the events and payloads
you can check [here](https://sellerapi.sellercenter.net/docs/entities-payload-definition).

## Index

- [Webhook Manager](#webhook-manager)
  - [Index](#index)
  - [Creating Webhooks](#creating-webhooks)
    - [createWebhook](#createwebhook)
      - [Example](#example)
  - [Getting Webhooks](#getting-webhooks)
    - [getAllWebhooks](#getallwebhooks)
      - [Example](#example-1)
  - [Deleting Webhooks](#deleting-webhooks)
    - [deleteWebhook](#deletewebhook)
      - [Example](#example-2)

-----------

## Creating Webhooks

### createWebhook

It's possible to register a url to process all the events using the following method.

| Parameter | Type | Description | Required | Default |
| --------- | ---- | ----------- | -------- | ------- |
| `$debug` | bool |  Whether it logs or not the request and response log | No | true | 

#### Example

```php
$webhookId = $this->sdk->webhooks()->createWebhook('http://example.com/webhooks');
```

The webhook creation will return an ID, make sure to save it if you want to operate with a specific one but you always 
can check all the webhooks and those ids with `getWebhooks`.

-----------

## Getting Webhooks

### getAllWebhooks

You can retrieve all the created webhooks by use `getWebhook` as follows.

| Parameter | Type | Description | Required | Default |
| --------- | ---- | ----------- | -------- | ------- |
| `$debug` | bool |  Whether it logs or not the request and response log | No | true | 

#### Example

```php
    $webhooks = $this->sdk->webhooks()->getAllWebhooks();
```

It's also possible to get a specific set of webhooks specifying their ids as an array. 

```php
    $webhooks = $this->sdk->webhooks()->getWebhooksByIds(['id-1', 'id-2', 'id-n']);
```

-----------

## Deleting Webhooks

It's important to know that each URL can be registered only ONE time, if you register the webhooks by your own and register some events if an update it's needed to add another event, first you need to delete it.

### deleteWebhook

You can delete a webhook just knowing their id using `deleteWebhook`

#### Example

```php
    $webhooks = $this->sdk->webhooks()->deleteWebhook(['id-1', 'id-2', 'id-n']);
```
-----------

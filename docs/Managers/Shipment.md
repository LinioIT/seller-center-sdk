# Shipment Manager

Manager to support Linio/Falabella shipment providers endpoints

## Index

- [Shipment Manager](#shipment-manager)
  - [Index](#index)
  - [Getting shipment providers](#getting-shipment-providers)
    - [getShipmentProvider](#getShipmentProviders)
      - [Example](#example)

-----------

## Getting shipment providers

### getShipmentProviders

Returns possible shipment providers.

| Parameter | Type | Description | Required | Default |
| --------- | ---- | ----------- | -------- | ------- |
| `$debug` | bool |  Whether it logs or not the request and response log | No | true | 

#### Example

```php
$shipmentProviders = $this->sdk->shipment()->getShipmentProviders();
```


-----------
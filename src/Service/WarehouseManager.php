<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Service;

use Linio\SellerCenter\Application\Parameters;
use Linio\SellerCenter\Factory\Xml\Warehouse\WarehousesFactory;

class WarehouseManager extends BaseManager
{
    public const DEFAULT_LIMIT = 100;
    public const DEFAULT_OFFSET = 0;
    public const VALID_WAREHOUSE_TYPE = ['only_shipments', 'only_returns'];

    /**
     * @return mixed[]
     */
    public function getWarehousesFromParameters(
        int $limit = self::DEFAULT_LIMIT,
        int $offset = self::DEFAULT_OFFSET,
        ?string $warehouseType = null,
        ?bool $isDefault = null,
        ?bool $isEnabled = null,
        bool $debug = true
    ): array {
        $action = 'GetWarehouse';

        $parameters = $this->makeParametersForAction($action);
        $this->setPagination($parameters, $limit, $offset);

        if (!empty($isDefault)) {
            $parameters->set(['IsDefault' => $isDefault]);
        }

        if (!empty($isEnabled)) {
            $parameters->set(['IsEnabled' => $isEnabled]);
        }

        if (!empty($warehouseType) && in_array($warehouseType, self::VALID_WAREHOUSE_TYPE)) {
            $parameters->set(['WarehouseType' => $warehouseType]);
        }

        $builtResponse = $this->executeAction(
            $action,
            $parameters,
            null,
            'GET',
            $debug
        );

        $warehouses = WarehousesFactory::make($builtResponse->getBody());

        return $warehouses->all();
    }

    /**
     * @return mixed[]
     */
    public function getWarehouseByFacilityId(
        string $facilityId,
        bool $debug = true
    ): array {
        $action = 'GetWarehouse';

        $parameters = $this->makeParametersForAction($action);

        if (!empty($facilityId)) {
            $parameters->set(['FacilityId' => $facilityId]);
        }

        $builtResponse = $this->executeAction(
            $action,
            $parameters,
            null,
            'GET',
            $debug
        );

        $warehouses = WarehousesFactory::make($builtResponse->getBody());

        return $warehouses->all();
    }

    /**
     * @return mixed[]
     */
    public function getWarehouseById(
        string $sellerWarehouseId,
        bool $debug = true
    ): array {
        $action = 'GetWarehouse';

        $parameters = $this->makeParametersForAction($action);

        if (!empty($sellerWarehouseId)) {
            $parameters->set(['SellerWarehouseId' => $sellerWarehouseId]);
        }

        $builtResponse = $this->executeAction(
            $action,
            $parameters,
            null,
            'GET',
            $debug
        );

        $warehouses = WarehousesFactory::make($builtResponse->getBody());

        return $warehouses->all();
    }

    public function setPagination(Parameters &$parameters, int $limit, int $offset): void
    {
        $parameters->set(
            [
                'Limit' => $limit,
                'Offset' => $offset,
            ]
        );
    }
}

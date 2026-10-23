<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Service;

use Linio\SellerCenter\Factory\Xml\Brand\BrandsFactory;
use Linio\SellerCenter\Model\Brand\Brand;

class BrandManager extends BaseManager
{
    /**
     * @return Brand[]
     */
    public function getBrands(bool $debug = true): array
    {
        $action = 'GetBrands';

        $parameters = $this->makeParametersForAction($action);

        $builtResponse = $this->executeAction(
            $action,
            $parameters,
            null,
            'GET',
            $debug
        );

        $brands = BrandsFactory::make($builtResponse->getBody());

        return array_values($brands->all());
    }
}

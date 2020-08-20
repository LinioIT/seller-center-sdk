<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Service;

use Linio\SellerCenter\Factory\Xml\Brand\BrandsFactory;

class BrandManager extends BaseManager
{
    private const GET_BRANDS_ACTION = 'GetBrands';

    public function getBrands(): array
    {
        $action = self::GET_BRANDS_ACTION;

        $requestId = $this->generateRequestId();

        $builtResponse = $this->executeAction($action, $requestId);

        $brands = BrandsFactory::make($builtResponse->getBody());

        $brandResponse = array_values($brands->all());

        $this->logger->info(
            sprintf(
                '%d::%s::APIResponse::SellerCenterSdk: %d brands was recovered',
                $requestId,
                $action,
                count($brands->all())
            )
        );

        return $brandResponse;
    }
}

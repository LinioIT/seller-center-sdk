<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Service;

use Linio\SellerCenter\Factory\Xml\Seller\StatisticsFactory;
use Linio\SellerCenter\Model\Seller\Statistic;

class SellerManager extends BaseManager
{
    public function getStatistics(): Statistic
    {
        $action = 'GetStatistics';

        $parameters = $this->makeParametersForAction($action);

        $requestId = $this->generateRequestId();

        $builtResponse = $this->executeAction($action, $parameters, $requestId, 'GET');

        return StatisticsFactory::make($builtResponse->getBody());
    }
}

<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Service;

use Linio\SellerCenter\Factory\Xml\Seller\StatisticsFactory;
use Linio\SellerCenter\Model\Seller\Statistic;

class BaseSellerManager extends BaseManager
{
    public function getStatistics(bool $debug = true): Statistic
    {
        $action = 'GetStatistics';

        $parameters = $this->makeParametersForAction($action);

        $builtResponse = $this->executeAction(
            $action,
            $parameters,
            null,
            'GET',
            $debug
        );

        return StatisticsFactory::make($builtResponse->getBody());
    }
}

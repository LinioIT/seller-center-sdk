<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Service;

use Linio\SellerCenter\Factory\Xml\Seller\SellerFactory;
use Linio\SellerCenter\Model\Seller\Seller;

class GlobalSellerManager extends BaseSellerManager
{
    public function getSellerByUser(bool $debug = true): Seller
    {
        $action = 'GetSellerByUser';

        $parameters = $this->makeParametersForAction($action);

        $builtResponse = $this->executeAction(
            $action,
            $parameters,
            null,
            'GET',
            $debug
        );

        return SellerFactory::make($builtResponse->getBody());
    }
}
<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Service;

use Linio\Component\Util\Json;
use Linio\SellerCenter\Application\Parameters;
use Linio\SellerCenter\Exception\EmptyArgumentException;
use Linio\SellerCenter\Factory\Xml\QualityControl\QualityControlsFactory;
use Linio\SellerCenter\Model\QualityControl\QualityControl;

class QualityControlManager extends BaseManager
{
    public const DEFAULT_LIMIT = 100;
    public const DEFAULT_OFFSET = 0;

    /**
     * @return QualityControl[]
     */
    protected function getQcStatus(
        Parameters $parameters,
        bool $debug = true
    ): array {
        $builtResponse = $this->executeAction(
            'GetQcStatus',
            $parameters,
            null,
            'GET',
            $debug
        );

        $qualityControls = QualityControlsFactory::make($builtResponse->getBody());

        return array_values($qualityControls->all());
    }

    /**
     * @return QualityControl[]
     */
    public function getAllQcStatus(
        int $limit = self::DEFAULT_LIMIT,
        int $offset = self::DEFAULT_OFFSET,
        bool $debug = true
    ): array {
        $parameters = $this->makeParametersForGetQcStatusAction();

        $this->setListDimensions($parameters, $limit, $offset);

        return $this->getQcStatus(
            $parameters,
            $debug
        );
    }

    /**
     * @param string[] $skuSellerList
     *
     * @return QualityControl[]
     */
    public function getQcStatusBySkuSellerList(
        array $skuSellerList = [],
        int $limit = self::DEFAULT_LIMIT,
        int $offset = self::DEFAULT_OFFSET,
        bool $debug = true
    ): array {
        $parameters = $this->makeParametersForGetQcStatusAction();

        if (empty($skuSellerList)) {
            throw new EmptyArgumentException('SkuSellerList');
        }

        $this->setListDimensions($parameters, $limit, $offset);

        $parameters->set(
            ['SkuSellerList' => Json::encode($skuSellerList)]
        );

        return $this->getQcStatus(
            $parameters,
            $debug
        );
    }

    protected function setListDimensions(Parameters &$parameters, int $limit, int $offset): void
    {
        $verifiedLimit = $limit >= 1 ? $limit : self::DEFAULT_LIMIT;
        $verifiedOffset = $offset < 0 ? self::DEFAULT_OFFSET : $offset;

        $parameters->set(
            [
                'Limit' => $verifiedLimit,
                'Offset' => $verifiedOffset,
            ]
        );
    }

    protected function makeParametersForGetQcStatusAction(): Parameters
    {
        return $this->makeParametersForAction('GetQcStatus');
    }
}

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
    private const GET_QC_STATUS_ACTION = 'GetQcStatus';

    protected function getQcStatus(Parameters $parameters): array
    {
        $action = self::GET_QC_STATUS_ACTION;

        $requestId = $this->generateRequestId();

        $builtResponse = $this->executeAction($action, $requestId, $parameters);

        $qualityControls = QualityControlsFactory::make($builtResponse->getBody());

        $qualityControlsResponse = array_values($qualityControls->all());

        $this->logger->info(
            sprintf(
                '%d::%s::APIResponse::SellerCenterSdk: %d quality controls was recovered',
                $requestId,
                $action,
                count($qualityControls->all())
            )
        );

        return $qualityControlsResponse;
    }

    /**
     * @return QualityControl[]
     */
    public function getAllQcStatus(int $limit = self::DEFAULT_LIMIT, int $offset = self::DEFAULT_OFFSET): array
    {
        $parameters = clone $this->parameters;

        $this->setListDimensions($parameters, $limit, $offset);

        return $this->getQcStatus($parameters);
    }

    /**
     * @return QualityControl[]
     */
    public function getQcStatusBySkuSellerList(
        array $skuSellerList = [],
        int $limit = self::DEFAULT_LIMIT,
        int $offset = self::DEFAULT_OFFSET
    ): array {
        $parameters = clone $this->parameters;

        if (empty($skuSellerList)) {
            throw new EmptyArgumentException('SkuSellerList');
        }

        $this->setListDimensions($parameters, $limit, $offset);

        $parameters->set(
            ['SkuSellerList' => Json::encode($skuSellerList)]
        );

        return $this->getQcStatus($parameters);
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
}

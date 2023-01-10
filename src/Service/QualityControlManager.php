<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Service;

use Linio\Component\Util\Json;
use Linio\SellerCenter\Application\Parameters;
use Linio\SellerCenter\Application\Security\Signature;
use Linio\SellerCenter\Exception\EmptyArgumentException;
use Linio\SellerCenter\Factory\RequestFactory;
use Linio\SellerCenter\Factory\Xml\QualityControl\QualityControlsFactory;
use Linio\SellerCenter\Formatter\LogMessageFormatter;
use Linio\SellerCenter\Model\QualityControl\QualityControl;
use Linio\SellerCenter\Response\HandleResponse;

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
        $action = 'GetQcStatus';

        $parameters->set(['Action' => $action]);
        $parameters->set([
            'Signature' => Signature::generate($parameters, $this->configuration->getKey())->get(),
        ]);

        $requestHeaders = $this->generateRequestHeaders();
        $requestId = $requestHeaders[self::REQUEST_ID_HEADER];

        $request = RequestFactory::make(
            'GET',
            $this->configuration->getEndpoint(),
            $requestHeaders
        );

        $response = $this->client->send($request, [
            'query' => $parameters->all(),
        ]);

        $body = (string) $response->getBody();
        $builtResponse = HandleResponse::parse($body);

        if ($debug) {
            $this->logger->debug(
                LogMessageFormatter::fromAction($requestId, $action, LogMessageFormatter::TYPE_REQUEST),
                [
                    'request' => [
                        'url' => (string) $request->getUri(),
                        'method' => $request->getMethod(),
                        'body' => (string) $request->getBody(),
                        'parameters' => $parameters->all(),
                    ],
                    'response' => [
                        'head' => $builtResponse->getHead()->asXML(),
                        'body' => $builtResponse->getBody()->asXML(),
                    ],
                ]
            );
        }

        HandleResponse::validate($body);

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
        $parameters = clone $this->parameters;

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
        $parameters = clone $this->parameters;

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
}

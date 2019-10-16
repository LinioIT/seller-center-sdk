<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Service;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Request;
use Linio\Component\Util\Json;
use Linio\SellerCenter\Application\Configuration;
use Linio\SellerCenter\Application\Parameters;
use Linio\SellerCenter\Application\Security\Signature;
use Linio\SellerCenter\Exception\EmptyArgumentException;
use Linio\SellerCenter\Factory\Xml\QualityControl\QualityControlsFactory;
use Linio\SellerCenter\Formatter\LogMessageFormatter;
use Linio\SellerCenter\Model\QualityControl\QualityControl;
use Linio\SellerCenter\Response\HandleResponse;
use Psr\Log\LoggerInterface;

class QualityControlManager
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var Configuration
     */
    protected $configuration;

    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var Parameters
     */
    protected $parameters;

    public const DEFAULT_LIMIT = 100;
    public const DEFAULT_OFFSET = 0;

    public function __construct(
        Configuration $configuration,
        ClientInterface $client,
        Parameters $parameters,
        LoggerInterface $logger
    ) {
        $this->configuration = $configuration;
        $this->client = $client;
        $this->parameters = $parameters;
        $this->logger = $logger;
    }

    protected function getQcStatus(Parameters $parameters): array
    {
        $action = 'GetQcStatus';

        $parameters->set(['Action' => $action]);
        $parameters->set([
            'Signature' => Signature::generate($parameters, $this->configuration->getKey())->get(),
        ]);

        $requestId = uniqid((string) mt_rand());

        $request = new Request('GET', $this->configuration->getEndpoint(), [
            'Request-ID' => $requestId,
        ]);

        $requestId = $request->getHeaderLine('Request-ID');

        $this->logger->debug(
            LogMessageFormatter::fromAction($requestId, $action, LogMessageFormatter::TYPE_REQUEST),
            [
                'url' => (string) $request->getUri(),
                'method' => $request->getMethod(),
                'body' => (string) $request->getBody(),
                'parameters' => $parameters->all(),
            ]
        );

        $response = $this->client->send($request, [
            'query' => $parameters->all(),
        ]);

        $body = (string) $response->getBody();

        $this->logger->debug(
            LogMessageFormatter::fromAction($requestId, $action, LogMessageFormatter::TYPE_RESPONSE),
            [
                'body' => $body,
            ]
        );

        $builtResponse = HandleResponse::parse($body);

        $this->logger->debug(
            LogMessageFormatter::fromAction($requestId, $action, LogMessageFormatter::TYPE_BUILT_RESPONSE),
            [
                'head' => $builtResponse->getHead()->asXML(),
                'body' => $builtResponse->getBody()->asXML(),
            ]
        );

        $qualityControls = QualityControlsFactory::make($builtResponse->getBody());

        $qualityControlsResponse = array_values($qualityControls->all());

        $this->logger->info(
            sprintf(
                '%d::%s::APIResponse::SellerCenterSdk: %d quality controls was recovered',
                $request->getHeaderLine('Request-ID'),
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

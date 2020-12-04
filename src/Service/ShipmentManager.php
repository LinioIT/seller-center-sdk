<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Service;

use Linio\SellerCenter\Application\Configuration;
use Linio\SellerCenter\Application\Parameters;
use Linio\SellerCenter\Application\Security\Signature;
use Linio\SellerCenter\Contract\ClientInterface;
use Linio\SellerCenter\Factory\RequestFactory;
use Linio\SellerCenter\Factory\Xml\Shipment\ShipmentProvidersFactory;
use Linio\SellerCenter\Formatter\LogMessageFormatter;
use Linio\SellerCenter\Response\HandleResponse;
use Psr\Log\LoggerInterface;

class ShipmentManager
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

    public function getShipmentProviders(): array
    {
        $action = 'GetShipmentProviders';

        $parameters = clone $this->parameters;
        $parameters->set(['Action' => $action]);
        $parameters->set([
            'Signature' => Signature::generate($parameters, $this->configuration->getKey())->get(),
        ]);

        $requestId = uniqid((string) mt_rand());

        $request = RequestFactory::make('GET', $this->configuration->getEndpoint(), [
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

        $shipmentProviders = ShipmentProvidersFactory::make($builtResponse->getBody());

        $shipmentProvidersResponse = $shipmentProviders->all();

        $this->logger->info(
            sprintf(
                '%d::%s::APIResponse::SellerCenterSdk: %d shipment providers was recovered',
                $request->getHeaderLine('Request-ID'),
                $action,
                count($shipmentProviders->all())
            )
        );

        return $shipmentProvidersResponse;
    }
}

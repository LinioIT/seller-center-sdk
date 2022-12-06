<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Service;

use Linio\SellerCenter\Application\Security\Signature;
use Linio\SellerCenter\Factory\RequestFactory;
use Linio\SellerCenter\Factory\Xml\Shipment\ShipmentProvidersFactory;
use Linio\SellerCenter\Formatter\LogMessageFormatter;
use Linio\SellerCenter\Model\Shipment\ShipmentProvider;
use Linio\SellerCenter\Response\HandleResponse;

class ShipmentManager extends BaseManager
{
    /**
     * @return  ShipmentProvider[]
     */
    public function getShipmentProviders(): array
    {
        $action = 'GetShipmentProviders';

        $parameters = clone $this->parameters;
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

        $shipmentProviders = ShipmentProvidersFactory::make($builtResponse->getBody());

        return $shipmentProviders->all();
    }
}

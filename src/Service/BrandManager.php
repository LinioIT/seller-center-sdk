<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Service;

use Linio\SellerCenter\Application\Security\Signature;
use Linio\SellerCenter\Factory\RequestFactory;
use Linio\SellerCenter\Factory\Xml\Brand\BrandsFactory;
use Linio\SellerCenter\Formatter\LogMessageFormatter;
use Linio\SellerCenter\Model\Brand\Brand;
use Linio\SellerCenter\Response\HandleResponse;

class BrandManager extends BaseManager
{
    /**
     * @return Brand[]
     */
    public function getBrands(): array
    {
        $action = 'GetBrands';

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

        $brands = BrandsFactory::make($builtResponse->getBody());

        return array_values($brands->all());
    }
}

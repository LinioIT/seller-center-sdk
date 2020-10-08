<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Adapter\Client;

use Http\Discovery\Psr17FactoryDiscovery;
use Linio\SellerCenter\Contract\ClientInterface;
use Psr\Http\Client\ClientInterface as PsrClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

class PsrClientAdapter implements ClientInterface
{
    /**
     * @var PsrClientInterface
     */
    private $client;

    public function __construct(PsrClientInterface $client)
    {
        $this->client = $client;
    }

    public function send(RequestInterface $request, array $options = []): ResponseInterface
    {
        $query = $options['query'] ?? null;

        if ($query) {
            $uriWithQuery = $this->appendQuery($request->getUri(), $query);
            $request = $request->withUri($uriWithQuery);
        }

        return $this->client->sendRequest($request);
    }

    public function appendQuery(UriInterface $endpoint, ?array $query): UriInterface
    {
        if (empty($query)) {
            return $endpoint;
        }

        $uriFactory = Psr17FactoryDiscovery::findUriFactory();
        $uriWithQueryString = sprintf('%s?%s', (string) $endpoint, http_build_query($query));

        return $uriFactory->createUri($uriWithQueryString);
    }
}

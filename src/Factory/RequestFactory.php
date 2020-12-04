<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Factory;

use Http\Discovery\Psr17FactoryDiscovery;
use Psr\Http\Message\RequestInterface;

class RequestFactory
{
    /**
     * @param mixed[] $headers
     */
    public static function make(
        string $method,
        string $uri,
        array $headers = [],
        string $body = null
    ): RequestInterface {
        $request = Psr17FactoryDiscovery::findRequestFactory()->createRequest($method, $uri);

        foreach ($headers as $name => $value) {
            $request = $request->withHeader($name, $value);
        }

        if ($body) {
            $body = Psr17FactoryDiscovery::findStreamFactory()->createStream($body);
            $request = $request->withBody($body);
        }

        return $request;
    }
}

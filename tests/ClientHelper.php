<?php

declare(strict_types=1);

namespace Linio\SellerCenter;

use Prophecy\Argument;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

trait ClientHelper
{
    public function createClientWithResponse($body, $statusCode = 200)
    {
        $response = $this->prophesize(ResponseInterface::class);

        $response
            ->getBody()
            ->willReturn($body);

        $response
            ->getStatusCode()
            ->willReturn($statusCode);

        $client = $this->prophesize(ClientInterface::class);
        $client
            ->sendRequest(Argument::type(RequestInterface::class))
            ->willReturn($response);

        return $client->reveal();
    }
}

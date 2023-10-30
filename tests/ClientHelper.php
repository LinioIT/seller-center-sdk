<?php

declare(strict_types=1);

namespace Linio\SellerCenter;

use GuzzleHttp\Client;
use Prophecy\Argument;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

trait ClientHelper
{
    public function createClientWithResponse(
        string $body,
        int $statusCode = 200,
        ?string $extraResponseBody = null,
        int $extraStatusCode = 200
    ) {
        $response = $this->prophesize(ResponseInterface::class);
        $response
            ->getBody()
            ->willReturn($body);

        $response
            ->getStatusCode()
            ->willReturn($statusCode);

        $client = $this->prophesize(Client::class);

        $client
            ->send(Argument::type(RequestInterface::class), Argument::type('array'))
            ->willReturn($response);

        if (!empty($extraResponseBody)) {
            $extraResponse = $this->prophesize(ResponseInterface::class);
            $extraResponse
                ->getBody()
                ->willReturn($extraResponseBody);

            $extraResponse
                ->getStatusCode()
                ->willReturn($extraStatusCode);

            $client
                ->send(Argument::type(RequestInterface::class), Argument::type('array'))
                ->willReturn($extraResponse, $response);
        }

        return $client->reveal();
    }
}
